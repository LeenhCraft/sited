"""
Script para preparación de datos de diabetes a partir de múltiples archivos Excel
"""
import os
import sys
import argparse
import numpy as np
import pandas as pd
import json
from datetime import datetime
from sklearn.preprocessing import StandardScaler
from collections import Counter

def process_excel_files(input_files, output_file, metadata_file=None, combine=True, verbose=True):
    """
    Procesa uno o más archivos Excel de datos de diabetes.
    
    Args:
        input_files: Lista de rutas a archivos Excel
        output_file: Ruta para guardar el CSV combinado
        metadata_file: Ruta para guardar metadatos (opcional)
        combine: Si es True, combina todos los archivos; si no, procesa solo el primero
        verbose: Si es True, imprime mensajes de progreso
    
    Returns:
        DataFrame con los datos procesados
    """
    if verbose:
        print(f"Procesando {len(input_files)} archivo(s) de datos...")

    all_dfs = []
    total_rows = 0
    total_files_processed = 0
    
    column_types = {
        "Edad": "int",
        "IMC": "int",
        "Ansiedad_Estres": "int",
        "Consumo_Grasas": "int", 
        "Sed_Hambre": "int",
        "Antecedentes_Glucosa": "int",
        "Vision_Borrosa": "int",
        "Cicatrizacion_Lenta": "int",
        "Cansancio_Debilidad": "int",
        "Hormigueo_Entumecimiento": "int",
        "Actividad_Fisica": "int",
        "Consumo_Frutas_Verduras": "int",
        "Antecedentes_Familiares": "int",
        "Tendencia": "category"
    }
    
    for file_path in input_files:
        if not os.path.exists(file_path):
            print(f"Error: Archivo no encontrado: {file_path}")
            continue
            
        try:
            if verbose:
                print(f"Leyendo archivo: {file_path}")
            
            # Leer Excel
            df = pd.read_excel(file_path)
            
            # Asegurar que los nombres de columnas son correctos
            expected_columns = list(column_types.keys())
            if not all(col in df.columns for col in expected_columns):
                missing_cols = [col for col in expected_columns if col not in df.columns]
                print(f"Advertencia: Faltan columnas en {file_path}: {missing_cols}")
                
                # Intentar mapear columnas por similitud
                for col in list(df.columns):
                    if col not in expected_columns and any(expected in col for expected in expected_columns):
                        for expected in expected_columns:
                            if expected in col:
                                print(f"  Mapeando columna '{col}' a '{expected}'")
                                df = df.rename(columns={col: expected})
                                break
            
            # Convertir tipos de datos
            for col, dtype in column_types.items():
                if col in df.columns:
                    try:
                        if dtype == "int":
                            df[col] = df[col].astype(int)
                        elif dtype == "category":
                            df[col] = df[col].astype('category')
                    except Exception as e:
                        print(f"Error al convertir columna {col} a {dtype}: {str(e)}")
                        
                        # Intentar limpiar valores no válidos
                        if dtype == "int":
                            df[col] = pd.to_numeric(df[col], errors='coerce').fillna(0).astype(int)
            
            # Manejar valores de Tendencia
            if 'Tendencia' in df.columns:
                # Si Tendencia es numérica, convertir a categórica
                if pd.api.types.is_numeric_dtype(df['Tendencia']):
                    tendency_map = {
                        0: 'Bajo', 
                        1: 'Moderado', 
                        2: 'Alto', 
                        3: 'Bajo/Moderado',
                        4: 'Bajo/Alto',
                        5: 'Moderado/Alto'
                    }
                    df['Tendencia'] = df['Tendencia'].map(tendency_map)
                
                # Asegurar que solo tenga valores permitidos
                valid_tendencies = ['Bajo', 'Moderado', 'Alto', 'Bajo/Moderado', 'Bajo/Alto', 'Moderado/Alto']
                mask = df['Tendencia'].isin(valid_tendencies)
                if not mask.all():
                    invalid_values = df.loc[~mask, 'Tendencia'].unique()
                    print(f"Advertencia: Valores no válidos en Tendencia: {invalid_values}")
                    print("Estos registros serán filtrados.")
                    df = df[mask]
            
            # Eliminar filas con valores faltantes
            initial_rows = len(df)
            df = df.dropna()
            rows_dropped = initial_rows - len(df)
            if rows_dropped > 0 and verbose:
                print(f"  Se eliminaron {rows_dropped} filas con valores faltantes")
            
            # Agregar a la lista de DataFrames
            all_dfs.append(df)
            total_rows += len(df)
            total_files_processed += 1
            
            if verbose:
                print(f"  Procesadas {len(df)} filas de {file_path}")
                
            # Si no se deben combinar, solo procesar el primer archivo
            if not combine and len(all_dfs) == 1:
                break
                
        except Exception as e:
            print(f"Error al procesar {file_path}: {str(e)}")
    
    if not all_dfs:
        print("Error: No se pudo procesar ningún archivo")
        return None
    
    # Combinar todos los DataFrames
    if len(all_dfs) > 1:
        combined_df = pd.concat(all_dfs, ignore_index=True)
        if verbose:
            print(f"Combinados {len(all_dfs)} archivos con un total de {len(combined_df)} filas")
    else:
        combined_df = all_dfs[0]
        if verbose:
            print(f"Procesado un archivo con {len(combined_df)} filas")
    
    # Guardar en CSV
    if output_file:
        combined_df.to_csv(output_file, index=False)
        if verbose:
            print(f"Datos guardados en: {output_file}")
    
    # Generar y guardar metadatos
    if metadata_file:
        metadata = generate_metadata(combined_df)
        with open(metadata_file, 'w') as f:
            json.dump(metadata, f, indent=2)
        if verbose:
            print(f"Metadatos guardados en: {metadata_file}")
    
    return combined_df

def generate_metadata(df):
    """
    Genera metadatos sobre el DataFrame procesado
    
    Args:
        df: DataFrame con los datos procesados
    
    Returns:
        Diccionario con metadatos
    """
    metadata = {
        "timestamp": datetime.now().strftime("%Y-%m-%d %H:%M:%S"),
        "rows_count": len(df),
        "columns": list(df.columns),
        "column_stats": {},
        "tendencia_distribution": {}
    }
    
    # Estadísticas por columna
    for col in df.columns:
        col_stats = {
            "dtype": str(df[col].dtype),
            "missing": int(df[col].isna().sum()),
            "unique_values": int(df[col].nunique())
        }
        
        if pd.api.types.is_numeric_dtype(df[col]):
            col_stats.update({
                "min": float(df[col].min()),
                "max": float(df[col].max()),
                "mean": float(df[col].mean()),
                "median": float(df[col].median()),
                "std": float(df[col].std())
            })
        
        metadata["column_stats"][col] = col_stats
    
    # Distribución de tendencias
    if 'Tendencia' in df.columns:
        tendency_counts = df['Tendencia'].value_counts().to_dict()
        total = sum(tendency_counts.values())
        
        for tendency, count in tendency_counts.items():
            metadata["tendencia_distribution"][tendency] = {
                "count": int(count),
                "percentage": round(100 * count / total, 2)
            }
    
    return metadata

def parse_arguments():
    """
    Parsea los argumentos de línea de comandos
    
    Returns:
        Argumentos parseados
    """
    parser = argparse.ArgumentParser(description='Procesa archivos Excel de diabetes para entrenamiento')
    parser.add_argument('--input', '-i', nargs='+', required=True, help='Rutas a los archivos Excel de entrada')
    parser.add_argument('--output', '-o', required=True, help='Ruta para guardar el CSV procesado')
    parser.add_argument('--metadata', '-m', help='Ruta para guardar los metadatos (JSON)')
    parser.add_argument('--no-combine', action='store_true', help='No combinar múltiples archivos')
    parser.add_argument('--quiet', '-q', action='store_true', help='Modo silencioso')
    
    return parser.parse_args()

if __name__ == "__main__":
    args = parse_arguments()
    
    output_dir = os.path.dirname(args.output)
    if output_dir and not os.path.exists(output_dir):
        os.makedirs(output_dir)
    
    metadata_file = args.metadata
    if not metadata_file and args.output:
        metadata_file = os.path.splitext(args.output)[0] + '_metadata.json'
    
    process_excel_files(
        args.input, 
        args.output, 
        metadata_file,
        not args.no_combine,
        not args.quiet
    )