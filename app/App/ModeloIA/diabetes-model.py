import pandas as pd
import numpy as np
import pickle
from sklearn.naive_bayes import GaussianNB
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix

# Función para entrenar el modelo con datos existentes
def train_diabetes_model(data_path):
    """
    Entrena un modelo Naive Bayes con datos de diabetes
    
    Args:
        data_path: Ruta al archivo CSV con los datos de entrenamiento
    
    Returns:
        model: Modelo entrenado
        scaler: Objeto para escalar los datos
        accuracy: Precisión del modelo en los datos de prueba
    """
    # Cargar dataset
    data = pd.read_csv(data_path)
    
    # Separar características y etiquetas
    X = data.drop('Tendencia', axis=1)
    y = data['Tendencia']
    
    # Convertir etiquetas a valores numéricos si son categóricas
    if y.dtype == object:
        y_map = {'Bajo': 0, 'Medio': 1, 'Alto': 2}
        y = y.map(y_map)
    
    # Dividir datos en conjuntos de entrenamiento y prueba
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
    
    # Escalar características para mejorar rendimiento
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)
    
    # Entrenar modelo Naive Bayes
    model = GaussianNB()
    model.fit(X_train_scaled, y_train)
    
    # Evaluar modelo
    y_pred = model.predict(X_test_scaled)
    accuracy = accuracy_score(y_test, y_pred)
    
    print(f"Precisión del modelo: {accuracy:.4f}")
    print("\nInforme de clasificación:")
    print(classification_report(y_test, y_pred, target_names=['Bajo', 'Medio', 'Alto']))
    print("\nMatriz de confusión:")
    print(confusion_matrix(y_test, y_pred))
    
    return model, scaler, accuracy

# Función para guardar el modelo y scaler
def save_model(model, scaler, model_path='diabetes_model.pkl', scaler_path='scaler.pkl'):
    """
    Guarda el modelo y scaler en archivos pickle
    
    Args:
        model: Modelo entrenado
        scaler: Objeto scaler ajustado
        model_path: Ruta para guardar el modelo
        scaler_path: Ruta para guardar el scaler
    """
    with open(model_path, 'wb') as f:
        pickle.dump(model, f)
    
    with open(scaler_path, 'wb') as f:
        pickle.dump(scaler, f)
    
    print(f"Modelo guardado en {model_path}")
    print(f"Scaler guardado en {scaler_path}")

# Función para predecir riesgo de diabetes
def predict_diabetes(input_data, model_path='diabetes_model.pkl', scaler_path='scaler.pkl'):
    """
    Predice la tendencia de diabetes basado en los datos de entrada
    
    Args:
        input_data: Lista o array con los valores para cada característica
        model_path: Ruta al archivo del modelo guardado
        scaler_path: Ruta al archivo del scaler guardado
    
    Returns:
        dict: Diccionario con la predicción y probabilidades
    """
    # Cargar modelo y scaler
    with open(model_path, 'rb') as f:
        model = pickle.load(f)
    
    with open(scaler_path, 'rb') as f:
        scaler = pickle.load(f)
    
    # Convertir input a array numpy y escalar
    input_array = np.array(input_data).reshape(1, -1)
    input_scaled = scaler.transform(input_array)
    
    # Realizar predicción
    prediction = model.predict(input_scaled)[0]
    probabilities = model.predict_proba(input_scaled)[0]
    
    # Mapear predicción numérica a categoría
    tendency_map = {0: 'Bajo', 1: 'Medio', 2: 'Alto'}
    tendency = tendency_map[prediction]
    
    return {
        "tendencia": tendency,
        "tendencia_numerica": int(prediction),
        "probabilidad_bajo": float(probabilities[0]),
        "probabilidad_medio": float(probabilities[1]),
        "probabilidad_alto": float(probabilities[2])
    }

# Ejemplo de uso para entrenamiento
if __name__ == "__main__":
    # Entrenar modelo (descomentar para usar)
    # model, scaler, accuracy = train_diabetes_model('diabetes_data.csv')
    # save_model(model, scaler)
    
    # Ejemplo de predicción
    sample_input = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]  # Valores de muestra
    result = predict_diabetes(sample_input)
    print("\nEjemplo de predicción:")
    print(f"Tendencia: {result['tendencia']}")
    print(f"Probabilidades: Bajo={result['probabilidad_bajo']:.4f}, Medio={result['probabilidad_medio']:.4f}, Alto={result['probabilidad_alto']:.4f}")