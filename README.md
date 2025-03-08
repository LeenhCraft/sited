# Sistema de Identificación de Tendencias de la Diabetes Grado 2

## Pagina para hacer commits

- [commitlint.io](https://commitlint.io/)

## Prefijos en la base de datos

En las tablas del sistema tenemos diferentes prefijos usados para clasificar las diferentes tablas
| Prefijo | Significado |
| ----------------- | --------------------------------- |
| sd* | Sistema diabetes |
| pr* | Sistema preguntas y respuestas |
| sis\_ | Sistema principal |

## Querys para limpiar

#### Limpiar personal medidco, usuarios y personas en el sistema

```sql
TRUNCATE TABLE sd_personal_medico;
TRUNCATE TABLE sd_personal_especialidad;
TRUNCATE TABLE sis_personal;
TRUNCATE TABLE sis_usuarios;

INSERT INTO `sis_usuarios` (`idusuario`, `idrol`, `idpersona`, `usu_usuario`, `usu_pass`, `usu_token`, `usu_activo`, `usu_estado`, `usu_primera`, `usu_twoauth`, `usu_code_twoauth`, `usu_fecha`) VALUES
(1, 1, 1, 'developer', '$2y$10$Fit/2psoTtAP.pctt2qiluYnf4vYcKqbGvFbZa.8/ngskf1HlwZvW', NULL, 1, 1, 0, 0, '', '2022-07-22 01:10:31');
INSERT INTO `sis_personal` (`idpersona`, `per_dni`, `per_nombre`, `per_celular`, `per_email`, `per_direcc`, `per_foto`, `per_estado`, `per_fecha`) VALUES
(1, 75933129, 'BENITES LOJA, ROCIO ISABEL', 987654321, 'rocioisabelbenitesloja@gmail.com', '', NULL, 1, '2022-07-22 01:09:20');
```

## Querys para insertar preguntas y respuestas

#### .

```sql
-- Limpiar tablas
TRUNCATE TABLE pr_preguntas;
TRUNCATE TABLE pr_respuestas;
TRUNCATE TABLE pr_tipo_respuestas;

-- Insertar datos en pr_tipo_respuestas
INSERT INTO pr_tipo_respuestas (nombre, descripcion, metadatos_requeridos, creado_por) VALUES
('Opción múltiple', 'Pregunta con varias opciones donde solo una es correcta', '{"opciones": ["array"], "correcta": "integer"}', 1),
('Selección múltiple', 'Pregunta con varias opciones donde se pueden elegir varias respuestas', '{"opciones": ["array"], "correctas": ["array"]}', 1),
('Verdadero/Falso', 'Pregunta donde la respuesta es verdadera o falsa', '{"correcta": "boolean"}', 1),
('Respuesta corta', 'Pregunta que requiere una respuesta breve de texto', '{"longitud_maxima": "integer"}', 1),
('Escala', 'Evaluación en una escala numérica', '{"min": "integer", "max": "integer"}', 1),
('Desarrollo', 'Pregunta que requiere una respuesta detallada', '{"palabras_minimas": "integer"}', 1),
('Escala Likert', 'Escala de frecuencia o intensidad con valores numéricos asociados', '{"opciones": ["array"], "valores": ["array"]}', 1);

-- Insertar datos en pr_preguntas
INSERT INTO pr_preguntas (id_usuario, titulo, orden, contenido, estado, creado_por) VALUES
(1, 'Evaluación inicial', 1, '¿Cómo calificaría su dolor en una escala del 1 al 10?', 'ACTIVA', 1),
(1, 'Antecedentes médicos', 2, '¿Ha tenido cirugías previas relacionadas con este problema?', 'ACTIVA', 1),
(2, 'Historial familiar', 3, '¿Existe historial familiar de esta condición?', 'ACTIVA', 2),
(2, 'Síntomas actuales', 4, 'Describa los síntomas que está experimentando actualmente', 'ACTIVA', 2),
(3, 'Medicación actual', 5, '¿Está tomando algún medicamento actualmente?', 'ACTIVA', 3),
(3, 'Alergias', 6, '¿Tiene alguna alergia conocida a medicamentos?', 'ACTIVA', 3),
(4, 'Hábitos', 7, '¿Con qué frecuencia realiza actividad física?', 'INACTIVA', 4),
(4, 'Seguimiento', 8, '¿Ha notado mejoría desde su última consulta?', 'ACTIVA', 4),
(5, 'Síntomas generales', 9, 'Seleccione todos los síntomas que ha experimentado en la última semana', 'ACTIVA', 2),
(5, 'Evaluación psicológica', 10, '¿QUÉ TAN FRECUENTE PRESENTA ALTOS NIVELES DE ANSIEDAD, ESTRÉS Y/O PREOCUPACIÓN?', 'ACTIVA', 2);

-- Insertar datos en pr_respuestas
-- Nota: Asegúrate que los id_pregunta e id_tipo_respuesta existan en las tablas correspondientes
INSERT INTO pr_respuestas (id_pregunta, id_usuario, id_tipo_respuesta, contenido, contenido_alternativo, metadatos, es_respuesta_aceptada) VALUES
(1, 5, 4, '8', 'Dolor intenso', '{"valor": 8, "comentario": "Dolor persistente en la zona lumbar"}', TRUE),
(1, 6, 4, '6', 'Dolor moderado', '{"valor": 6, "comentario": "Dolor intermitente"}', TRUE),
(2, 5, 2, 'Sí', 'Cirugía de columna hace 5 años', '{"detalles": "Hernias L4-L5", "año": 2019}', TRUE),
(2, 6, 2, 'No', 'Sin cirugías previas', '{"detalles": "NA"}', TRUE),
(3, 7, 2, 'Sí', 'Padre y abuelo con la misma condición', '{"familiares": ["padre", "abuelo paterno"]}', TRUE),
(4, 7, 5, 'Dolor en la parte baja de la espalda que se irradia hacia la pierna derecha', 'Dolor radicular', '{"duracion": "2 semanas", "intensidad": "moderada a severa"}', TRUE),
(5, 8, 5, 'Paracetamol 500mg cada 8 horas', 'Analgésicos', '{"medicamentos": [{"nombre": "Paracetamol", "dosis": "500mg", "frecuencia": "8h"}]}', TRUE),
(6, 8, 2, 'No', 'Sin alergias conocidas', '{"confirmado": true}', TRUE),
(7, 9, 1, 'Tres veces por semana', 'Actividad regular', '{"tipo": "cardio y pesas", "duracion": "1 hora"}', FALSE),
(8, 9, 2, 'Sí', 'Mejoría notable', '{"porcentaje": 70, "areas": ["movilidad", "dolor"]}', TRUE),
(9, 10, 2, 'Fiebre, dolor de cabeza, fatiga', 'Síntomas múltiples', '{"opciones": ["Fiebre", "Dolor de cabeza", "Fatiga", "Tos", "Dolor muscular", "Náuseas", "Vómitos"], "seleccionadas": [0, 1, 2], "comentario": "Síntomas comenzaron hace 3 días"}', TRUE),
(10, 11, 7, 'A veces', 'Nivel moderado', '{"opciones": ["Casi Nunca", "A veces", "Casi Siempre"], "valores": [0, 1, 2], "seleccionada": 1, "valor_seleccionado": 1}', TRUE),
(10, 12, 7, 'Casi Siempre', 'Nivel alto', '{"opciones": ["Casi Nunca", "A veces", "Casi Siempre"], "valores": [0, 1, 2], "seleccionada": 2, "valor_seleccionado": 2}', TRUE),
(10, 13, 7, 'Casi Nunca', 'Nivel bajo', '{"opciones": ["Casi Nunca", "A veces", "Casi Siempre"], "valores": [0, 1, 2], "seleccionada": 0, "valor_seleccionado": 0}', TRUE);
```

### Limpiar tablas relacionadas al test

```sql
TRUNCATE TABLE sd_test_preguntas;
TRUNCATE TABLE sd_test;
```


# Documentación de `data-preparation.py`

## Descripción
Script para preparar y combinar datos de diabetes desde archivos Excel, con validación, conversión de tipos y generación de metadatos.

## Uso básico
```bash
python data-preparation.py --input archivo1.xlsx archivo2.xlsx --output datos_procesados.csv
```

## Argumentos
| Argumento | Abreviación | Descripción | Requerido |
|-----------|-------------|-------------|-----------|
| `--input` | `-i` | Rutas a los archivos Excel (acepta múltiples archivos) | Sí |
| `--output` | `-o` | Ruta para guardar el CSV procesado | Sí |
| `--metadata` | `-m` | Ruta para guardar los metadatos en JSON | No |
| `--no-combine` | - | No combinar múltiples archivos | No |
| `--quiet` | `-q` | Modo silencioso, sin mensajes de progreso | No |

## Estructura de datos esperada
El script espera archivos Excel con las siguientes columnas:

| Columna | Tipo |
|---------|------|
| Edad | int |
| IMC | int |
| Ansiedad_Estres | int |
| Consumo_Grasas | int |
| Sed_Hambre | int |
| Antecedentes_Glucosa | int |
| Vision_Borrosa | int |
| Cicatrizacion_Lenta | int |
| Cansancio_Debilidad | int |
| Hormigueo_Entumecimiento | int |
| Actividad_Fisica | int |
| Consumo_Frutas_Verduras | int |
| Antecedentes_Familiares | int |
| Tendencia | category ("Bajo", "Moderado", "Alto", "Bajo/Moderado", "Bajo/Alto", "Moderado/Alto") |

## Ejemplos de uso

### Procesar un único archivo
```bash
python data-preparation.py --input datos.xlsx --output datos_procesados.csv
```

### Combinar múltiples archivos
```bash
python data-preparation.py --input datos1.xlsx datos2.xlsx datos3.xlsx --output datos_combinados.csv
```

### Especificar ruta para metadatos
```bash
python data-preparation.py --input datos.xlsx --output datos_procesados.csv --metadata estadisticas.json
```

### Modo silencioso
```bash
python data-preparation.py --input datos.xlsx --output datos_procesados.csv --quiet
```

### Procesar solo el primer archivo
```bash
python data-preparation.py --input datos1.xlsx datos2.xlsx --output datos_procesados.csv --no-combine
```

## Salidas
1. **CSV con datos procesados**: Contiene los datos validados, con tipos corregidos y filas incompletas eliminadas
2. **Archivo JSON con metadatos**: Incluye estadísticas y distribución de valores por columna (generado automáticamente si no se especifica ruta)

## Funcionalidad
- Validación y corrección automática de nombres de columnas
- Conversión de tipos de datos
- Manejo de valores no válidos y missing
- Eliminación de filas incompletas
- Combinación opcional de múltiples archivos
- Generación de estadísticas y metadatos

# Documentación de `model-training.py`

## Descripción
Script para entrenar y evaluar modelos de predicción de diabetes usando Naive Bayes, con manejo de desbalance de clases, selección de características y generación de métricas detalladas.

## Uso básico
```bash
python model-training.py --input datos_procesados.csv --output-dir ./models
```

## Argumentos

| Argumento | Abreviación | Descripción | Requerido | Valor predeterminado |
|-----------|-------------|-------------|-----------|----------------------|
| `--input` | `-i` | Ruta al archivo CSV con datos de entrenamiento | Sí | - |
| `--output-dir` | `-o` | Directorio para guardar modelo y resultados | No | `./models` |
| `--model-name` | `-n` | Nombre base para el modelo | No | `diabetes_model` |
| `--model-type` | `-t` | Tipo de modelo Naive Bayes | No | `gaussian` |
| `--balance-method` | `-b` | Método para manejar desbalance | No | `smote` |
| `--feature-selection` | `-f` | Activar selección de características | No | `False` |
| `--num-features` | `-k` | Número de características a seleccionar | No | `10` |
| `--test-size` | `-s` | Proporción de datos para prueba | No | `0.2` |
| `--no-scale` | - | No escalar características | No | `False` |

## Opciones específicas

### Tipos de modelo (`--model-type`)
- `gaussian`: GaussianNB (adecuado para variables continuas)
- `multinomial`: MultinomialNB (adecuado para conteos)
- `bernoulli`: BernoulliNB (adecuado para datos binarios)

### Métodos de balance (`--balance-method`)
- `smote`: Aplica SMOTE para sobremuestrear clases minoritarias
- `class_weight`: Usa pesos de clase para ajustar la importancia
- `none`: No aplica corrección de desbalance

## Ejemplos de uso

### Entrenamiento básico
```bash
python model-training.py --input datos_procesados.csv
```

### Especificar tipo de modelo
```bash
python model-training.py --input datos_procesados.csv --model-type bernoulli
```

### Activar selección de características
```bash
python model-training.py --input datos_procesados.csv --feature-selection --num-features 8
```

### Cambiar método de balance de clases
```bash
python model-training.py --input datos_procesados.csv --balance-method class_weight
```

### Entrenar sin escalar datos
```bash
python model-training.py --input datos_procesados.csv --no-scale
```

### Configuración personalizada completa
```bash
python model-training.py --input datos_procesados.csv --output-dir ./mis_modelos \
                        --model-name diabetes_nb --model-type multinomial \
                        --balance-method smote --feature-selection --num-features 6 \
                        --test-size 0.25
```

## Salidas

El script genera los siguientes archivos en el directorio de salida:

1. **Modelo entrenado**: `[model_name].pkl`
2. **Scaler** (si se usa): `[model_name]_scaler.pkl` 
3. **Metadatos**: `[model_name]_metadata.json`
4. **Visualizaciones**:
   - Matriz de confusión: `[model_name]_confusion_matrix.png`
   - Curvas ROC: `[model_name]_roc_curves.png`
   - Importancia de características: `[model_name]_feature_importance.png`
   - Distribución de probabilidades: `[model_name]_probability_distribution.png`

## Funcionalidad

- Carga y preprocesamiento de datos
- Escalado de características
- Selección de las k mejores características
- Manejo de desbalance de clases mediante SMOTE o pesos
- Entrenamiento de modelos Naive Bayes (Gaussian, Multinomial, Bernoulli)
- Validación cruzada
- Métricas detalladas de rendimiento (accuracy, precision, recall, f1, AUC)
- Visualizaciones de resultados
- Guardado de modelo, scaler y metadatos