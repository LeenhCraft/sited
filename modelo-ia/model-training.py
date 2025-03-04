"""
Script para entrenar y evaluar modelo de predicción de diabetes con Naive Bayes
Maneja evaluación dinámica y genera informes detallados
"""
import os
import sys
import argparse
import numpy as np
import pandas as pd
import json
import pickle
import joblib
from datetime import datetime
import matplotlib.pyplot as plt
import seaborn as sns
from collections import Counter

from sklearn.model_selection import train_test_split, cross_val_score, StratifiedKFold
from sklearn.naive_bayes import GaussianNB, MultinomialNB, BernoulliNB
from sklearn.preprocessing import StandardScaler, MinMaxScaler
from sklearn.metrics import (
    accuracy_score, precision_score, recall_score, f1_score,
    confusion_matrix, classification_report, roc_curve, auc
)
from sklearn.feature_selection import SelectKBest, chi2, f_classif
from sklearn.utils.class_weight import compute_class_weight
from imblearn.over_sampling import SMOTE

def load_and_prepare_data(data_file, target_col='Tendencia', test_size=0.2, random_state=42, scale=True):
    """
    Carga y prepara los datos para entrenamiento
    
    Args:
        data_file: Ruta al archivo CSV con los datos
        target_col: Nombre de la columna objetivo
        test_size: Proporción de datos para prueba
        random_state: Semilla para reproducibilidad
        scale: Si es True, escala los datos
    
    Returns:
        X_train, X_test, y_train, y_test, scaler, feature_names
    """
    print(f"Cargando datos desde: {data_file}")
    
    # Cargar datos
    df = pd.read_csv(data_file)
    print(f"Datos cargados: {df.shape[0]} filas, {df.shape[1]} columnas")
    
    # Separar características y etiquetas
    if target_col not in df.columns:
        raise ValueError(f"La columna objetivo '{target_col}' no existe en el dataset")
    
    # Verificar tipo de datos de la columna objetivo
    y = df[target_col]
    
    # Si la columna objetivo es categórica o string, convertir a valores numéricos
    if pd.api.types.is_categorical_dtype(y) or pd.api.types.is_string_dtype(y):
        tendency_map = {
            'Bajo': 0, 
            'Moderado': 1, 
            'Alto': 2, 
            'Bajo/Moderado': 3,
            'Bajo/Alto': 4,
            'Moderado/Alto': 5
        }
        y = y.map(tendency_map)
        print(f"Convertidos valores de tendencia a numéricos: {Counter(y)}")
    
    # Obtener características
    X = df.drop(columns=[target_col])
    feature_names = X.columns.tolist()
    
    # Dividir en conjuntos de entrenamiento y prueba
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=test_size, random_state=random_state, stratify=y
    )
    
    print(f"División de datos: {X_train.shape[0]} muestras de entrenamiento, {X_test.shape[0]} muestras de prueba")
    
    # Escalar características
    scaler = None
    if scale:
        scaler = StandardScaler()
        X_train = scaler.fit_transform(X_train)
        X_test = scaler.transform(X_test)
        print("Datos escalados con StandardScaler")
    
    return X_train, X_test, y_train, y_test, scaler, feature_names

def select_best_features(X_train, y_train, X_test, feature_names, k=10, scoring=f_classif):
    """
    Selecciona las mejores características
    
    Args:
        X_train, y_train: Datos de entrenamiento
        X_test: Datos de prueba
        feature_names: Nombres de las características
        k: Número de características a seleccionar
        scoring: Función de puntuación (f_classif, chi2, etc.)
    
    Returns:
        X_train_selected, X_test_selected, selected_features, feature_scores
    """
    k = min(k, len(feature_names))
    selector = SelectKBest(score_func=scoring, k=k)
    X_train_selected = selector.fit_transform(X_train, y_train)
    X_test_selected = selector.transform(X_test)
    
    # Obtener índices de características seleccionadas
    selected_indices = selector.get_support(indices=True)
    selected_features = [feature_names[i] for i in selected_indices]
    
    # Obtener puntuaciones
    feature_scores = {}
    for i, (feature, score) in enumerate(zip(feature_names, selector.scores_)):
        feature_scores[feature] = {
            'score': float(score),
            'p_value': float(selector.pvalues_[i]) if hasattr(selector, 'pvalues_') else None,
            'selected': i in selected_indices
        }
    
    print(f"Seleccionadas {k} mejores características: {', '.join(selected_features)}")
    
    return X_train_selected, X_test_selected, selected_features, feature_scores

def handle_class_imbalance(X_train, y_train, method='smote', random_state=42):
    """
    Maneja el desbalance de clases
    
    Args:
        X_train, y_train: Datos de entrenamiento
        method: Método para manejar desbalance ('smote', 'class_weight', None)
        random_state: Semilla para reproducibilidad
    
    Returns:
        X_train_balanced, y_train_balanced, class_weights
    """
    # Verificar balance de clases
    class_counts = Counter(y_train)
    total = sum(class_counts.values())
    
    print("Distribución de clases original:")
    for cls, count in class_counts.items():
        print(f"  Clase {cls}: {count} ({100 * count / total:.2f}%)")
    
    # Si hay desbalance significativo (una clase < 30% de la más numerosa)
    max_count = max(class_counts.values())
    imbalanced = any(count < 0.3 * max_count for count in class_counts.values())
    
    class_weights = None
    
    if not imbalanced:
        print("No se detectó desbalance significativo en las clases")
        return X_train, y_train, None
    
    if method == 'smote':
        print("Aplicando SMOTE para balancear clases...")
        smote = SMOTE(random_state=random_state)
        X_train_balanced, y_train_balanced = smote.fit_resample(X_train, y_train)
        
        # Mostrar nuevo balance
        new_counts = Counter(y_train_balanced)
        new_total = sum(new_counts.values())
        print("Distribución de clases después de SMOTE:")
        for cls, count in new_counts.items():
            print(f"  Clase {cls}: {count} ({100 * count / new_total:.2f}%)")
        
        return X_train_balanced, y_train_balanced, None
        
    elif method == 'class_weight':
        print("Calculando pesos de clases...")
        classes = np.unique(y_train)
        class_weights = compute_class_weight(class_weight='balanced', classes=classes, y=y_train)
        class_weights = dict(zip(classes, class_weights))
        
        print("Pesos de clases calculados:")
        for cls, weight in class_weights.items():
            print(f"  Clase {cls}: {weight:.4f}")
        
        return X_train, y_train, class_weights
    
    else:
        print(f"Método '{method}' para manejar desbalance no reconocido o ninguno especificado")
        return X_train, y_train, None

def train_and_evaluate_model(
    X_train, X_test, y_train, y_test, 
    model_type='gaussian', 
    class_weights=None,
    feature_names=None,
    output_dir=None,
    model_name='diabetes_model'
):
    """
    Entrena y evalúa un modelo Naive Bayes
    
    Args:
        X_train, X_test, y_train, y_test: Datos de entrenamiento y prueba
        model_type: Tipo de modelo Naive Bayes ('gaussian', 'multinomial', 'bernoulli')
        class_weights: Pesos de clases para manejar desbalance
        feature_names: Nombres de las características
        output_dir: Directorio para guardar visualizaciones y resultados
        model_name: Nombre base para el archivo del modelo
    
    Returns:
        model, metrics, visualizations_paths
    """
    print(f"Entrenando modelo Naive Bayes de tipo: {model_type}")
    
    # Crear directorio para visualizaciones si no existe
    visualizations = {}
    if output_dir and not os.path.exists(output_dir):
        os.makedirs(output_dir)
    
    # Seleccionar el tipo de modelo
    if model_type == 'gaussian':
        model = GaussianNB(priors=class_weights)
    elif model_type == 'multinomial':
        model = MultinomialNB(class_prior=class_weights)
    elif model_type == 'bernoulli':
        model = BernoulliNB(class_prior=class_weights)
    else:
        raise ValueError(f"Tipo de modelo '{model_type}' no reconocido")
    
    # Entrenar el modelo
    model.fit(X_train, y_train)
    print("Modelo entrenado")
    
    # Realizar predicciones
    y_pred = model.predict(X_test)
    y_prob = model.predict_proba(X_test)
    
    # Calcular métricas
    metrics = calculate_metrics(y_test, y_pred, y_prob)
    print_metrics(metrics)
    
    # Generar visualizaciones
    if output_dir:
        visualizations = generate_visualizations(
            model, X_train, X_test, y_train, y_test, y_pred, y_prob,
            feature_names, output_dir, model_name
        )
    
    # Cross-validation
    cv_scores = cross_val_score(model, X_train, y_train, cv=5, scoring='accuracy')
    metrics['cross_validation'] = {
        'scores': cv_scores.tolist(),
        'mean': float(cv_scores.mean()),
        'std': float(cv_scores.std())
    }
    print(f"Validación cruzada (5-fold): {cv_scores.mean():.4f} ± {cv_scores.std():.4f}")
    
    return model, metrics, visualizations

def calculate_metrics(y_true, y_pred, y_prob):
    """
    Calcula métricas de rendimiento del modelo
    
    Args:
        y_true: Etiquetas verdaderas
        y_pred: Predicciones del modelo
        y_prob: Probabilidades de predicción
    
    Returns:
        Diccionario con métricas
    """
    # Calcular matriz de confusión
    cm = confusion_matrix(y_true, y_pred)
    
    # Calcular métricas básicas
    accuracy = float(accuracy_score(y_true, y_pred))
    
    # Calcular métricas por clase y promedio
    precision = {
        'micro': float(precision_score(y_true, y_pred, average='micro')),
        'macro': float(precision_score(y_true, y_pred, average='macro')),
        'weighted': float(precision_score(y_true, y_pred, average='weighted')),
        'per_class': precision_score(y_true, y_pred, average=None).tolist()
    }
    
    recall = {
        'micro': float(recall_score(y_true, y_pred, average='micro')),
        'macro': float(recall_score(y_true, y_pred, average='macro')),
        'weighted': float(recall_score(y_true, y_pred, average='weighted')),
        'per_class': recall_score(y_true, y_pred, average=None).tolist()
    }
    
    f1 = {
        'micro': float(f1_score(y_true, y_pred, average='micro')),
        'macro': float(f1_score(y_true, y_pred, average='macro')),
        'weighted': float(f1_score(y_true, y_pred, average='weighted')),
        'per_class': f1_score(y_true, y_pred, average=None).tolist()
    }
    
    # Calcular curvas ROC y AUC por clase (one-vs-rest)
    n_classes = len(np.unique(y_true))
    roc_auc = {}
    fpr = {}
    tpr = {}
    
    for i in range(n_classes):
        # Asegurarse de que haya al menos un ejemplo de la clase
        if np.sum(y_true == i) > 0:
            fpr[i], tpr[i], _ = roc_curve((y_true == i).astype(int), y_prob[:, i])
            roc_auc[i] = auc(fpr[i], tpr[i])
        else:
            roc_auc[i] = 0.0
    
    # Clasificación detallada
    class_report = classification_report(y_true, y_pred, output_dict=True)
    
    # Compilar todas las métricas
    metrics = {
        'accuracy': accuracy,
        'precision': precision,
        'recall': recall,
        'f1_score': f1,
        'confusion_matrix': cm.tolist(),
        'classification_report': class_report,
        'roc_auc': {str(k): v for k, v in roc_auc.items()},
        'auc_macro': float(sum(roc_auc.values()) / len(roc_auc))
    }
    
    return metrics

def print_metrics(metrics):
    """
    Imprime métricas de rendimiento del modelo
    
    Args:
        metrics: Diccionario con métricas
    """
    print("\n--- Métricas del Modelo ---")
    print(f"Accuracy: {metrics['accuracy']:.4f}")
    print(f"Precision (macro): {metrics['precision']['macro']:.4f}")
    print(f"Recall (macro): {metrics['recall']['macro']:.4f}")
    print(f"F1 Score (macro): {metrics['f1_score']['macro']:.4f}")
    print(f"AUC (macro): {metrics['auc_macro']:.4f}")
    
    print("\nMatriz de Confusión:")
    cm = np.array(metrics['confusion_matrix'])
    print(cm)
    
    print("\nMétricas por clase:")
    class_report = metrics['classification_report']
    for cls in sorted([k for k in class_report.keys() if k not in ['accuracy', 'macro avg', 'weighted avg']]):
        print(f"  Clase {cls}:")
        print(f"    Precision: {class_report[cls]['precision']:.4f}")
        print(f"    Recall: {class_report[cls]['recall']:.4f}")
        print(f"    F1 Score: {class_report[cls]['f1-score']:.4f}")
        print(f"    Support: {class_report[cls]['support']}")

def generate_visualizations(
    model, X_train, X_test, y_train, y_test, y_pred, y_prob,
    feature_names, output_dir, model_name
):
    """
    Genera visualizaciones para el análisis del modelo
    
    Args:
        model: Modelo entrenado
        X_train, X_test, y_train, y_test: Datos de entrenamiento y prueba
        y_pred: Predicciones del modelo
        y_prob: Probabilidades de predicción
        feature_names: Nombres de las características
        output_dir: Directorio para guardar visualizaciones
        model_name: Nombre base para los archivos
    
    Returns:
        Diccionario con rutas a las visualizaciones
    """
    visualizations = {}
    
    try:
        # Mapear etiquetas numéricas a categorías para visualización
        category_names = ['Bajo', 'Moderado', 'Alto', 'Bajo/Moderado', 'Bajo/Alto', 'Moderado/Alto']
        n_classes = len(np.unique(np.concatenate([y_test, y_pred])))
        
        # 1. Matriz de confusión
        plt.figure(figsize=(12, 10))
        cm = confusion_matrix(y_test, y_pred)
        
        # Usar solo las etiquetas para las clases que existen en los datos
        labels = category_names[:n_classes]
        
        sns.heatmap(cm, annot=True, fmt='d', cmap='Blues', 
                    xticklabels=labels, yticklabels=labels)
        plt.xlabel('Predicción')
        plt.ylabel('Verdadero')
        plt.title('Matriz de Confusión')
        cm_path = os.path.join(output_dir, f"{model_name}_confusion_matrix.png")
        plt.savefig(cm_path)
        plt.close()
        visualizations['confusion_matrix'] = cm_path
        
        # 2. Curvas ROC
        plt.figure(figsize=(12, 10))
        colors = ['blue', 'green', 'red', 'orange', 'purple', 'brown']
        
        for i, color in zip(range(n_classes), colors[:n_classes]):
            if np.sum(y_test == i) > 0:  # Solo si hay ejemplos de esta clase
                fpr, tpr, _ = roc_curve((y_test == i).astype(int), y_prob[:, i])
                roc_auc = auc(fpr, tpr)
                plt.plot(fpr, tpr, color=color, lw=2,
                         label=f'ROC clase {category_names[i]} (AUC = {roc_auc:.2f})')
        
        plt.plot([0, 1], [0, 1], 'k--', lw=2)
        plt.xlim([0.0, 1.0])
        plt.ylim([0.0, 1.05])
        plt.xlabel('Tasa de Falsos Positivos')
        plt.ylabel('Tasa de Verdaderos Positivos')
        plt.title('Curvas ROC')
        plt.legend(loc="lower right")
        roc_path = os.path.join(output_dir, f"{model_name}_roc_curves.png")
        plt.savefig(roc_path)
        plt.close()
        visualizations['roc_curves'] = roc_path
        
        # 3. Importancia de características para Naive Bayes
        if feature_names:
            plt.figure(figsize=(12, 8))
            
            # Para GaussianNB, podemos usar theta_ (medias por clase) como indicador
            if hasattr(model, 'theta_') and len(feature_names) <= 20:
                feature_importance = np.std(model.theta_, axis=0)
                sorted_idx = np.argsort(feature_importance)
                plt.barh(range(len(sorted_idx)), feature_importance[sorted_idx])
                plt.yticks(range(len(sorted_idx)), [feature_names[i] for i in sorted_idx])
                plt.xlabel('Importancia relativa (desviación estándar entre clases)')
                plt.title('Importancia de características en el modelo Naive Bayes')
                importance_path = os.path.join(output_dir, f"{model_name}_feature_importance.png")
                plt.savefig(importance_path)
                plt.close()
                visualizations['feature_importance'] = importance_path
        
        # 4. Distribución de probabilidades por clase
        plt.figure(figsize=(10, 6))
        for i, color, name in zip(range(n_classes), colors[:n_classes], category_names[:n_classes]):
            if i < y_prob.shape[1]:  # Asegurarse de que la clase está en las probabilidades
                # Obtener probabilidades para esta clase
                probs = y_prob[:, i]
                plt.hist(probs, bins=20, alpha=0.5, color=color, label=name)
        
        plt.xlabel('Probabilidad predicha')
        plt.ylabel('Frecuencia')
        plt.title('Distribución de probabilidades predichas por clase')
        plt.legend()
        prob_dist_path = os.path.join(output_dir, f"{model_name}_probability_distribution.png")
        plt.savefig(prob_dist_path)
        plt.close()
        visualizations['probability_distribution'] = prob_dist_path
        
    except Exception as e:
        print(f"Error al generar visualizaciones: {str(e)}")
    
    return visualizations

def save_model(model, scaler, feature_names, metrics, output_dir, model_name):
    """
    Guarda el modelo entrenado y metadatos relacionados
    
    Args:
        model: Modelo entrenado
        scaler: Scaler para normalizar datos
        feature_names: Nombres de características
        metrics: Métricas del modelo
        output_dir: Directorio de salida
        model_name: Nombre base para los archivos
    
    Returns:
        Diccionario con rutas a los archivos guardados
    """
    if not os.path.exists(output_dir):
        os.makedirs(output_dir)
    
    # Guardar modelo
    model_path = os.path.join(output_dir, f"{model_name}.pkl")
    with open(model_path, 'wb') as f:
        pickle.dump(model, f)
    
    # Guardar scaler si existe
    scaler_path = None
    if scaler is not None:
        scaler_path = os.path.join(output_dir, f"{model_name}_scaler.pkl")
        with open(scaler_path, 'wb') as f:
            pickle.dump(scaler, f)
    
    # Guardar metadatos
    metadata = {
        'model_name': model_name,
        'model_type': type(model).__name__,
        'feature_names': feature_names,
        'training_date': datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        'metrics': metrics,
        'model_params': model.get_params()
    }
    
    metadata_path = os.path.join(output_dir, f"{model_name}_metadata.json")
    with open(metadata_path, 'w') as f:
        json.dump(metadata, f, indent=2)
    
    print(f"Modelo guardado en: {model_path}")
    if scaler_path:
        print(f"Scaler guardado en: {scaler_path}")
    print(f"Metadatos guardados en: {metadata_path}")
    
    return {
        'model': model_path,
        'scaler': scaler_path,
        'metadata': metadata_path
    }

def parse_arguments():
    """
    Parsea los argumentos de línea de comandos
    
    Returns:
        Argumentos parseados
    """
    parser = argparse.ArgumentParser(description='Entrena y evalúa modelo de predicción de diabetes')
    parser.add_argument('--input', '-i', required=True, help='Ruta al archivo CSV con los datos de entrenamiento')
    parser.add_argument('--output-dir', '-o', default='./models', help='Directorio para guardar modelo y resultados')
    parser.add_argument('--model-name', '-n', default='diabetes_model', help='Nombre base para el modelo')
    parser.add_argument('--model-type', '-t', choices=['gaussian', 'multinomial', 'bernoulli'], default='gaussian',
                        help='Tipo de modelo Naive Bayes')
    parser.add_argument('--balance-method', '-b', choices=['smote', 'class_weight', 'none'], default='smote',
                        help='Método para manejar desbalance de clases')
    parser.add_argument('--feature-selection', '-f', action='store_true',
                        help='Aplicar selección de características')
    parser.add_argument('--num-features', '-k', type=int, default=10,
                        help='Número de características a seleccionar')
    parser.add_argument('--test-size', '-s', type=float, default=0.2,
                        help='Proporción de datos para prueba (0.0-1.0)')
    parser.add_argument('--no-scale', action='store_true',
                        help='No escalar características')
    
    return parser.parse_args()

def main():
    """
    Función principal
    """
    args = parse_arguments()
    
    # Crear directorio de salida si no existe
    if not os.path.exists(args.output_dir):
        os.makedirs(args.output_dir)
    
    # Cargar y preparar datos
    X_train, X_test, y_train, y_test, scaler, feature_names = load_and_prepare_data(
        args.input,
        test_size=args.test_size,
        scale=not args.no_scale
    )
    
    # Selección de características
    if args.feature_selection:
        X_train, X_test, selected_features, feature_scores = select_best_features(
            X_train, y_train, X_test, feature_names, k=args.num_features
        )
        feature_names = selected_features
    
    # Manejar desbalance de clases
    balance_method = None if args.balance_method == 'none' else args.balance_method
    X_train, y_train, class_weights = handle_class_imbalance(X_train, y_train, method=balance_method)
    
    # Entrenar y evaluar modelo
    model, metrics, visualizations = train_and_evaluate_model(
        X_train, X_test, y_train, y_test,
        model_type=args.model_type,
        class_weights=class_weights,
        feature_names=feature_names,
        output_dir=args.output_dir,
        model_name=args.model_name
    )
    
    # Guardar modelo y metadatos
    save_model(
        model, scaler, feature_names, metrics,
        args.output_dir, args.model_name
    )
    
    print("\nEntrenamiento y evaluación completados con éxito.")

if __name__ == "__main__":
    main()