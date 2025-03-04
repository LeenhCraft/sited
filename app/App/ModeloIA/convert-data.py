import pandas as pd
import numpy as np
import sys

def convert_excel_to_csv(excel_path, csv_path):
    """
    Convierte un archivo Excel con datos de tendencia de diabetes a CSV
    
    Args:
        excel_path: Ruta al archivo Excel
        csv_path: Ruta donde guardar el CSV resultante
    """
    try:
        # Leer archivo Excel
        df = pd.read_excel(excel_path)
        
        # Renombrar columnas si es necesario
        # Asegúrate de que los nombres de las columnas coincidan con tu Excel
        column_mapping = {
            'Edad': 'Edad',
            'IMC': 'IMC',
            'Ansiedad_Estres': 'Ansiedad_Estres',
            'Consumo_Grasas': 'Consumo_Grasas',
            'Sed_Hambre': 'Sed_Hambre',
            'Antecedentes_Glucosa': 'Antecedentes_Glucosa',
            'Vision_Borrosa': 'Vision_Borrosa',
            'Cicatrizacion_Lenta': 'Cicatrizacion_Lenta',
            'Cansancio_Debilidad': 'Cansancio_Debilidad',
            'Hormigueo_Entumecimiento': 'Hormigueo_Entumecimiento',
            'Actividad_Fisica': 'Actividad_Fisica',
            'Consumo_Frutas_Verduras': 'Consumo_Frutas_Verduras',
            'Antecedentes_Familiares': 'Antecedentes_Familiares',
            'Tendencia': 'Tendencia'
        }
        
        # Aplicar mapeo de columnas si es necesario
        df = df.rename(columns=column_mapping)
        
        # Verificar valores de tendencia y convertir si es necesario
        if 'Tendencia' in df.columns and df['Tendencia'].dtype == object:
            # Mapear valores de texto a numéricos si es necesario
            tendency_map = {'Bajo': 0, 'Medio': 1, 'Alto': 2}
            if df['Tendencia'].isin(['Bajo', 'Medio', 'Alto']).all():
                # Si todos los valores son textuales, convertir
                print("Convirtiendo valores de tendencia de texto a numéricos...")
                df['Tendencia'] = df['Tendencia'].map(tendency_map)
        
        # Guardar como CSV
        df.to_csv(csv_path, index=False)
        print(f"Archivo convertido exitosamente y guardado en {csv_path}")
        print(f"Primeras 5 filas del archivo convertido:")
        print(df.head())
        
    except Exception as e:
        print(f"Error al convertir archivo: {str(e)}")
        sys.exit(1)

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Uso: python convert_excel_to_csv.py archivo_excel.xlsx archivo_salida.csv")
        sys.exit(1)
    
    excel_path = sys.argv[1]
    csv_path = sys.argv[2]
    
    convert_excel_to_csv(excel_path, csv_path)