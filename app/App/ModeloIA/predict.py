#!/usr/bin/env python3
import sys
import json
import pickle
import numpy as np

def predict_diabetes(input_data):
    """
    Predice la tendencia de diabetes basado en los datos de entrada
    
    Args:
        input_data: Lista con los valores de entrada
    
    Returns:
        dict: Diccionario con la predicción y probabilidades
    """
    try:
        # Cargar modelo y scaler
        with open('diabetes_model.pkl', 'rb') as f:
            model = pickle.load(f)
        
        with open('scaler.pkl', 'rb') as f:
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
            "success": True,
            "tendencia": tendency,
            "tendencia_numerica": int(prediction),
            "probabilidad_bajo": float(probabilities[0]),
            "probabilidad_medio": float(probabilities[1]),
            "probabilidad_alto": float(probabilities[2])
        }
    except Exception as e:
        return {
            "success": False,
            "error": str(e)
        }

if __name__ == "__main__":
    try:
        # Obtener datos desde argumentos
        input_json = sys.argv[1]
        input_data = json.loads(input_json)
        
        # Realizar predicción
        result = predict_diabetes(input_data)
        
        # Devolver resultado en formato JSON
        print(json.dumps(result))
    except Exception as e:
        error_result = {
            "success": False,
            "error": str(e)
        }
        print(json.dumps(error_result))