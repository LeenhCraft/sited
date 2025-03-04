"""
Script para realizar predicciones con el modelo entrenado
"""
import os
import sys
import argparse
import numpy as np
import pandas as pd
import json
import pickle

def load_model_and_scaler(model_path, scaler_path=None):
    """
    Carga el modelo entrenado y el scaler (si existe)
    
    Args:
        model_path: Ruta al archivo del modelo
        scaler_path: Ruta al archivo del scaler (opcional)
    
    Returns:
        model, scaler
    """
    # Cargar modelo
    with open(model_path, 'rb') as f:
        model = pickle.load(f)
    
    # Cargar scaler si existe
    scaler = None
    if scaler_path and os.path.exists(scaler_path):
        with open(scaler_path, 'rb') as f:
            scaler = pickle.load(f)
    
    return model, scaler

def predict_single(model, scaler, input_data, feature_names=None):
    """
    Realiza una predicción para un solo conjunto de datos
    
    Args:
        model: Modelo entrenado
        scaler: Scaler para normalizar datos (opcional)
        input_data: Lista o array con los valores de entrada
        feature_names: Lista de nombres de características
    
    Returns:
        prediction, probabilities
    """
    # Convertir a array numpy
    input_array = np.array(input_data).reshape(1, -1)
    
    # Escalar si es necesario
    if scaler:
        input_array = scaler.transform(input_array)
    
    # Realizar predicción
    prediction = model.predict(input_array)[0]
    probabilities = model.predict_proba(input_array)[0]
    
    return prediction, probabilities

def predict_batch(model, scaler, input_csv, output_csv=None, feature_names=None):
    """
    Realiza predicciones para un lote de datos desde un CSV
    
    Args:
        model: Modelo entrenado
        scaler: Scaler para normalizar datos (opcional)
        input_csv: Ruta al archivo CSV con datos de entrada
        output_csv: Ruta para guardar resultados (opcional)
        feature_names: Lista de nombres de características
    
    Returns:
        DataFrame con resultados
    """
    # Cargar datos
    df = pd.read_csv(input_csv)
    
    # Verificar características
    if feature_names:
        missing_features = [f for f in feature_names if f not in df.columns]
        if missing_features:
            raise ValueError(f"Faltan características en el CSV: {missing_features}")
        
        # Usar solo las características necesarias en el orden correcto
        X = df[feature_names].values
    else:
        # Si no se especifican características, usar todas las columnas
        X = df.values
    
    # Escalar si es necesario
    if scaler:
        X = scaler.transform(X)
    
    # Realizar predicciones
    predictions = model.predict(X)
    probabilities = model.predict_proba(X)
    
    # Crear DataFrame de resultados
    results = df.copy()
    
    # Mapear predicciones numéricas a categorías
    category_map = {
        0: 'Bajo', 
        1: 'Moderado', 
        2: 'Alto', 
        3: 'Bajo/Moderado',
        4: 'Bajo/Alto',
        5: 'Moderado/Alto'
    }
    
    results['prediction'] = [category_map.get(p, str(p)) for p in predictions]
    
    # Añadir probabilidades para cada clase
    for i, category in category_map.items():
        if i < probabilities.shape[1]:  # Solo si el modelo tiene esta clase
            results[f'prob_{category}'] = probabilities[:, i]
    
    # Guardar resultados si se especifica una ruta
    if output_csv:
        results.to_csv(output_csv, index=False)
        print(f"Resultados guardados en: {output_csv}")
    
    return results

def load_metadata(metadata_path):
    """
    Carga metadatos del modelo
    
    Args:
        metadata_path: Ruta al archivo de metadatos
    
    Returns:
        Diccionario con metadatos
    """
    if not os.path.exists(metadata_path):
        return None
    
    with open(metadata_path, 'r') as f:
        return json.load(f)

def format_prediction(prediction, probabilities, category_map=None):
    """
    Formatea el resultado de la predicción para mostrar
    
    Args:
        prediction: Valor numérico de la predicción
        probabilities: Array con probabilidades para cada clase
        category_map: Mapeo de valores numéricos a categorías
    
    Returns:
        Texto formateado con la predicción
    """
    if category_map is None:
        category_map = {
            0: 'Bajo', 
            1: 'Moderado', 
            2: 'Alto', 
            3: 'Bajo/Moderado',
            4: 'Bajo/Alto',
            5: 'Moderado/Alto'
        }
    
    # Obtener categoría
    category = category_map.get(prediction, f"Desconocido ({prediction})")
    
    # Formatear texto
    result = f"Predicción: {category}\n\nProbabilidades:\n"
    
    # Añadir probabilidades
    for i, prob in enumerate(probabilities):
        if i in category_map:
            result += f"  {category_map[i]}: {prob:.4f} ({prob*100:.2f}%)\n"
    
    return result

def parse_arguments():
    """
    Parsea los argumentos de línea de comandos
    
    Returns:
        Argumentos parseados
    """
    parser = argparse.ArgumentParser(description='Realizar predicciones con modelo entrenado')
    parser.add_argument('--model', '-m', required=True, help='Ruta al archivo del modelo')
    parser.add_argument('--scaler', '-s', help='Ruta al archivo del scaler')
    parser.add_argument('--metadata', help='Ruta al archivo de metadatos')
    
    # Grupo para modo de predicción
    mode_group = parser.add_mutually_exclusive_group(required=True)
    mode_group.add_argument('--input-values', '-i', nargs='+', type=float, help='Valores de entrada para predicción individual')
    mode_group.add_argument('--input-csv', '-c', help='Ruta al CSV con datos para predicción por lotes')
    
    parser.add_argument('--output-csv', '-o', help='Ruta para guardar resultados (solo para predicción por lotes)')
    parser.add_argument('--features', '-f', nargs='+', help='Nombres de características (en orden)')
    
    return parser.parse_args()

def main():
    """
    Función principal
    """
    args = parse_arguments()
    
    # Cargar modelo y scaler
    model, scaler = load_model_and_scaler(args.model, args.scaler)
    
    # Cargar metadatos si existen
    metadata = None
    if args.metadata:
        metadata = load_metadata(args.metadata)
    
    # Obtener nombres de características
    feature_names = args.features
    if not feature_names and metadata and 'feature_names' in metadata:
        feature_names = metadata['feature_names']
    
    # Predicción individual
    if args.input_values:
        if feature_names and len(args.input_values) != len(feature_names):
            print(f"Error: El número de valores ({len(args.input_values)}) no coincide con el número de características ({len(feature_names)})")
            return
        
        prediction, probabilities = predict_single(model, scaler, args.input_values, feature_names)
        
        # Mostrar resultado
        result = format_prediction(prediction, probabilities)
        print("\n" + "="*50)
        print(result)
        print("="*50)
    
    # Predicción por lotes
    elif args.input_csv:
        try:
            results = predict_batch(model, scaler, args.input_csv, args.output_csv, feature_names)
            print(f"Procesadas {len(results)} predicciones")
            
            # Mostrar algunas estadísticas
            prediction_counts = results['prediction'].value_counts()
            print("\nDistribución de predicciones:")
            for category, count in prediction_counts.items():
                print(f"  {category}: {count} ({100*count/len(results):.2f}%)")
        
        except Exception as e:
            print(f"Error al procesar predicciones por lotes: {str(e)}")

if __name__ == "__main__":
    main()