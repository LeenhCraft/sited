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
(1, 76144152, 'desarrollador', 987654321, 'hackingleenh@gmail.com', '', NULL, 1, '2022-07-22 01:09:20');

--72845692
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