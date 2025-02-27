<?php

namespace App\Controllers\Chio;

use App\Core\Controller;
use App\Models\TableModel;
use Exception;

class PreguntasController extends Controller
{
    private const PERMISSION = "ruta.medicos";

    public function __construct()
    {
        parent::__construct();
    }

    public function index($request, $response)
    {
        return $this->render($response, "Chio.Preguntas.Preguntas", [
            "titulo_web" => "Preguntas",
            "url" => $request->getUri()->getPath(),
            'permisos' => $this->permisos_extras,
            "permission" => self::PERMISSION,
            "js" => [
                "/js/chio/preguntas.js?v=" . time()
            ]
        ]);
    }

    public function list($request, $response)
    {
        try {
            $model = new TableModel();
            $model->setTable("pr_preguntas");
            $model->setId("id_pregunta");

            // Usar el modelo para hacer la consulta con joins
            $preguntas = $model->select(
                "pr_preguntas.id_pregunta",
                "pr_preguntas.titulo",
                "pr_preguntas.contenido",
                "pr_preguntas.estado",
                "pr_preguntas.orden",
                "pr_tipo_respuestas.nombre as tipo_respuesta"
            )
                ->leftJoin("pr_respuestas", "pr_preguntas.id_pregunta", "pr_respuestas.id_pregunta")
                ->leftJoin("pr_tipo_respuestas", "pr_respuestas.id_tipo_respuesta", "pr_tipo_respuestas.id_tipo_respuesta")
                ->where("pr_preguntas.eliminado", "0")
                ->orderBy("pr_preguntas.orden")
                ->get();

            // Procesar para obtener preguntas únicas
            $preguntasUnicas = [];
            $idsProcesados = [];

            foreach ($preguntas as $pregunta) {
                if (!in_array($pregunta['id_pregunta'], $idsProcesados)) {
                    $idsProcesados[] = $pregunta['id_pregunta'];
                    $preguntasUnicas[$pregunta['id_pregunta']] = $pregunta;
                    $preguntasUnicas[$pregunta['id_pregunta']]['respuestas'] = [];
                }
            }

            // Obtener las opciones de respuesta para cada pregunta
            foreach ($preguntasUnicas as &$pregunta) {
                $modelRespuestas = new TableModel();
                $modelRespuestas->setTable("pr_respuestas");
                $modelRespuestas->setId("id_respuesta");

                $respuestas = $modelRespuestas
                    ->select("id_respuesta", "contenido", "metadatos")
                    ->where("id_pregunta", $pregunta['id_pregunta'])
                    ->get();

                if ($pregunta['tipo_respuesta'] === 'Escala Likert') {
                    // Para Escala Likert, procesamos todas las respuestas
                    foreach ($respuestas as $respuesta) {
                        if (!empty($respuesta['contenido'])) {
                            $pregunta['respuestas'][] = $respuesta['contenido'];
                        }
                    }
                } elseif ($pregunta['tipo_respuesta'] === 'Selección múltiple') {
                    // Para selección múltiple, procesamos las opciones desde metadatos
                    if (!empty($respuestas)) {
                        $metadatos = json_decode($respuestas[0]['metadatos'], true);
                        if (isset($metadatos['opciones'])) {
                            $pregunta['respuestas'] = $metadatos['opciones'];
                        }
                    }
                } else {
                    // Para otros tipos de respuesta
                    if (!empty($respuestas)) {
                        if (!empty($respuestas[0]['contenido'])) {
                            $pregunta['respuestas'] = [$respuestas[0]['contenido']];
                        } else {
                            // En caso de no tener contenido, intenta extraer desde los metadatos
                            $metadatos = json_decode($respuestas[0]['metadatos'], true);
                            if (isset($metadatos['opciones'])) {
                                $pregunta['respuestas'] = $metadatos['opciones'];
                            }
                        }
                    }
                }
            }

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => array_values($preguntasUnicas)
            ]);
        } catch (Exception $e) {
            return $response->withJson([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function tiposRespuesta($request, $response)
    {
        try {
            $model = new TableModel();
            $model->setTable("pr_tipo_respuestas");
            $model->setId("id_tipo_respuesta");
            $tipos = $model->select("id_tipo_respuesta", "nombre")
                ->where("eliminado", 0)
                ->get();

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $tipos
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function guardar($request, $response)
    {
        try {
            // Obtener datos del cuerpo de la solicitud
            $data = $request->getParsedBody();

            // Validar datos básicos requeridos
            if (empty($data['titulo']) || empty($data['contenido']) || empty($data['id_tipo_respuesta'])) {
                throw new Exception("Faltan datos requeridos");
            }

            // Crear modelo y comenzar transacción
            $model = new TableModel();
            $model->beginTransaction();

            // Obtener información del tipo de respuesta
            $modelTipoRespuesta = new TableModel();
            $modelTipoRespuesta->setTable("pr_tipo_respuestas");
            $modelTipoRespuesta->setId("id_tipo_respuesta");

            $tipoRespuesta = $modelTipoRespuesta
                ->select(
                    "id_tipo_respuesta",
                    "nombre",
                    "descripcion"
                )
                ->where("id_tipo_respuesta", $data['id_tipo_respuesta'])
                ->first();

            if (!$tipoRespuesta) {
                throw new Exception("Tipo de respuesta no válido");
            }

            // Obtener el último orden para asignar el nuevo si no se especifica
            $orden = !empty($data['orden']) ? intval($data['orden']) : null;
            if (!$orden) {
                $modelOrden = new TableModel();
                $modelOrden->setTable("pr_preguntas");
                $ultimoOrden = $modelOrden->select("MAX(orden) as ultimo_orden")->get();
                $orden = 1;
                if (!empty($ultimoOrden) && isset($ultimoOrden[0]['ultimo_orden'])) {
                    $orden = (int)$ultimoOrden[0]['ultimo_orden'] + 1;
                }
            }

            // Preparar datos para la pregunta
            $preguntaData = [
                'id_usuario' => $_SESSION['usuario_id'] ?? 1, // Asignar ID de usuario de la sesión
                'titulo' => $data['titulo'],
                'contenido' => $data['contenido'],
                'orden' => $orden,
                'estado' => $data['estado'] ?? 'Activo', // Estado por defecto
                'creado_por' => $_SESSION['usuario_id'] ?? 1,
            ];

            // Guardar la pregunta
            $modelPregunta = new TableModel();
            $modelPregunta->setTable("pr_preguntas");
            $modelPregunta->setId("id_pregunta");
            $pregunta = $modelPregunta->create($preguntaData);

            if (!$pregunta) {
                throw new Exception("Error al guardar la pregunta");
            }

            $idPregunta = $pregunta['id_pregunta'];

            // Preparar metadatos según el tipo de respuesta
            $metadatos = isset($data['metadatos']) ? $data['metadatos'] : '{}';
            if (!is_string($metadatos)) {
                $metadatos = json_encode($metadatos);
            }

            // Procesar según el tipo de respuesta
            if ($tipoRespuesta['nombre'] === 'Escala Likert') {
                // Decodificar los metadatos para obtener las opciones y valores
                $metadatosArray = json_decode($metadatos, true);

                if (isset($metadatosArray['opciones']) && isset($metadatosArray['valores'])) {
                    $opciones = $metadatosArray['opciones'];
                    $valores = $metadatosArray['valores'];

                    // Crear una respuesta para cada opción de la escala Likert
                    for ($i = 0; $i < count($opciones); $i++) {
                        $opcion = $opciones[$i];
                        $valor = $valores[$i];

                        // Determinar contenido alternativo según el valor
                        $contenidoAlternativo = '';
                        if ($valor == 0) {
                            $contenidoAlternativo = 'Nivel bajo';
                        } elseif ($valor == 1) {
                            $contenidoAlternativo = 'Nivel moderado';
                        } elseif ($valor == 2) {
                            $contenidoAlternativo = 'Nivel alto';
                        }

                        // Crear metadatos específicos para esta opción
                        $opcionMetadatos = [
                            'opciones' => $opciones,
                            'valores' => $valores,
                            'seleccionada' => $i,
                            'valor_seleccionado' => $valor
                        ];

                        // Guardar la respuesta individual
                        $respuestaData = [
                            'id_pregunta' => $idPregunta,
                            'id_usuario' => $_SESSION['usuario_id'] ?? 1,
                            'id_tipo_respuesta' => $data['id_tipo_respuesta'],
                            'contenido' => $opcion,
                            'contenido_alternativo' => $contenidoAlternativo,
                            'metadatos' => json_encode($opcionMetadatos),
                            'es_respuesta_aceptada' => 1
                        ];

                        $modelRespuesta = new TableModel();
                        $modelRespuesta->setTable("pr_respuestas");
                        $modelRespuesta->setId("id_respuesta");
                        $respuesta = $modelRespuesta->create($respuestaData);

                        if (!$respuesta) {
                            throw new Exception("Error al guardar la respuesta para la opción: " . $opcion);
                        }
                    }
                } else {
                    throw new Exception("Formato de metadatos inválido para Escala Likert");
                }
            } else {
                // Para otros tipos de respuesta, mantener el comportamiento original
                // Determinar contenido de respuesta según el tipo
                $contenidoRespuesta = '';

                switch ($tipoRespuesta['nombre']) {
                    case 'Opción múltiple':
                    case 'Selección múltiple':
                        // Para estos tipos no se guarda contenido directo
                        $contenidoRespuesta = '';
                        break;

                    case 'Verdadero/Falso':
                        $contenidoRespuesta = 'Verdadero/Falso';
                        break;

                    case 'Respuesta corta':
                        $contenidoRespuesta = $data['respuesta_ejemplo'] ?? '';
                        break;

                    case 'Escala':
                        $contenidoRespuesta = $data['descripcion_escala'] ?? '';
                        break;

                    case 'Desarrollo':
                        $contenidoRespuesta = $data['guia_respuesta'] ?? '';
                        break;

                    default:
                        $contenidoRespuesta = $data['contenido_respuesta'] ?? '';
                }

                // Preparar datos para la respuesta
                $respuestaData = [
                    'id_pregunta' => $idPregunta,
                    'id_usuario' => $_SESSION['usuario_id'] ?? 1,
                    'id_tipo_respuesta' => $data['id_tipo_respuesta'],
                    'contenido' => $contenidoRespuesta,
                    'contenido_alternativo' => '',
                    'metadatos' => $metadatos,
                    'es_respuesta_aceptada' => 1
                ];

                // Guardar la respuesta
                $modelRespuesta = new TableModel();
                $modelRespuesta->setTable("pr_respuestas");
                $modelRespuesta->setId("id_respuesta");
                $respuesta = $modelRespuesta->create($respuestaData);

                if (!$respuesta) {
                    throw new Exception("Error al guardar la respuesta");
                }
            }

            // Confirmar transacción
            $model->commit();

            return $this->respondWithJson($response, [
                'success' => true,
                'message' => 'Pregunta guardada correctamente',
                'id_pregunta' => $idPregunta
            ]);
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if (isset($model) && method_exists($model, 'rollBack')) {
                $model->rollBack();
            }

            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function actualizar($request, $response, $args)
    {
        try {
            $id = $args['id'] ?? null;

            if (!$id) {
                throw new Exception("ID de pregunta no proporcionado");
            }

            // Obtener datos del cuerpo de la solicitud
            $data = $request->getParsedBody();

            // Validar datos requeridos
            if (empty($data['titulo']) || empty($data['contenido']) || empty($data['id_tipo_respuesta'])) {
                throw new Exception("Faltan datos requeridos");
            }

            // Crear modelo y comenzar transacción
            $model = new TableModel();
            $model->beginTransaction();

            // Obtener información del tipo de respuesta
            $modelTipoRespuesta = new TableModel();
            $modelTipoRespuesta->setTable("pr_tipo_respuestas");
            $modelTipoRespuesta->setId("id_tipo_respuesta");

            $tipoRespuesta = $modelTipoRespuesta
                ->select("id_tipo_respuesta", "nombre", "descripcion")
                ->where("id_tipo_respuesta", $data['id_tipo_respuesta'])
                ->first();

            if (!$tipoRespuesta) {
                throw new Exception("Tipo de respuesta no válido");
            }

            // Actualizar pregunta
            $modelPregunta = new TableModel();
            $modelPregunta->setTable("pr_preguntas");
            $modelPregunta->setId("id_pregunta");

            $preguntaData = [
                'titulo' => $data['titulo'],
                'contenido' => $data['contenido'],
                'orden' => $data['orden'] ?? 1,
                'estado' => $data['estado'] ?? 'Activo',
                'ultima_actualizacion' => date("Y-m-d H:i:s"),
                'actualizado_por' => $_SESSION['usuario_id'] ?? 1,
            ];

            $actualizarPregunta = $modelPregunta->update($id, $preguntaData);

            if (!$actualizarPregunta) {
                throw new Exception("Error al actualizar la pregunta");
            }

            // Obtener las respuestas existentes
            $modelRespuesta = new TableModel();
            $modelRespuesta->setTable("pr_respuestas");
            $modelRespuesta->setId("id_respuesta");

            // Preparar metadatos según el tipo de respuesta
            $metadatos = isset($data['metadatos']) ? $data['metadatos'] : '{}';
            if (!is_string($metadatos)) {
                $metadatos = json_encode($metadatos);
            }

            // Para Escala Likert, manejar de forma especial
            if ($tipoRespuesta['nombre'] === 'Escala Likert') {
                // Primero eliminar todas las respuestas existentes para esta pregunta
                $eliminarRespuestas = $modelRespuesta->delete("id_pregunta = $id");

                // Decodificar los metadatos para obtener las opciones y valores
                $metadatosArray = json_decode($metadatos, true);

                if (isset($metadatosArray['opciones']) && isset($metadatosArray['valores'])) {
                    $opciones = $metadatosArray['opciones'];
                    $valores = $metadatosArray['valores'];

                    // Crear una respuesta para cada opción de la escala Likert
                    for ($i = 0; $i < count($opciones); $i++) {
                        $opcion = $opciones[$i];
                        $valor = $valores[$i];

                        // Determinar contenido alternativo según el valor
                        $contenidoAlternativo = '';
                        if ($valor == 0) {
                            $contenidoAlternativo = 'Nivel bajo';
                        } elseif ($valor == 1) {
                            $contenidoAlternativo = 'Nivel moderado';
                        } elseif ($valor >= 2) {
                            $contenidoAlternativo = 'Nivel alto';
                        }

                        // Crear metadatos específicos para esta opción
                        $opcionMetadatos = [
                            'opciones' => $opciones,
                            'valores' => $valores,
                            'seleccionada' => $i,
                            'valor_seleccionado' => $valor
                        ];

                        // Guardar la respuesta individual
                        $respuestaData = [
                            'id_pregunta' => $id,
                            'id_usuario' => $_SESSION['usuario_id'] ?? 1,
                            'id_tipo_respuesta' => $data['id_tipo_respuesta'],
                            'contenido' => $opcion,
                            'contenido_alternativo' => $contenidoAlternativo,
                            'metadatos' => json_encode($opcionMetadatos),
                            'es_respuesta_aceptada' => 1
                        ];

                        $respuesta = $modelRespuesta->create($respuestaData);

                        if (!$respuesta) {
                            throw new Exception("Error al guardar la respuesta para la opción: " . $opcion);
                        }
                    }
                } else {
                    throw new Exception("Formato de metadatos inválido para Escala Likert");
                }
            } else {
                // Para otros tipos de respuesta, mantener el comportamiento original
                // Determinar contenido de respuesta según el tipo
                $contenidoRespuesta = '';

                switch ($tipoRespuesta['nombre']) {
                    case 'Opción múltiple':
                    case 'Selección múltiple':
                        // Para estos tipos no se guarda contenido directo
                        $contenidoRespuesta = '';
                        break;

                    case 'Verdadero/Falso':
                        $contenidoRespuesta = 'Verdadero/Falso';
                        break;

                    case 'Respuesta corta':
                        $contenidoRespuesta = $data['respuesta_ejemplo'] ?? '';
                        break;

                    case 'Escala':
                        $contenidoRespuesta = $data['descripcion_escala'] ?? '';
                        break;

                    case 'Desarrollo':
                        $contenidoRespuesta = $data['guia_respuesta'] ?? '';
                        break;

                    default:
                        $contenidoRespuesta = $data['contenido_respuesta'] ?? '';
                }

                // Obtener la respuesta existente
                $respuestaExistente = $modelRespuesta->select("id_respuesta")
                    ->where("id_pregunta", $id)
                    ->first();

                // Preparar datos para la respuesta
                $respuestaData = [
                    'id_tipo_respuesta' => $data['id_tipo_respuesta'],
                    'contenido' => $contenidoRespuesta,
                    'metadatos' => $metadatos
                ];

                // Actualizar o crear respuesta
                if ($respuestaExistente) {
                    $respuesta = $modelRespuesta->update($respuestaExistente['id_respuesta'], $respuestaData);
                    if (!$respuesta) {
                        throw new Exception("Error al actualizar la respuesta");
                    }
                } else {
                    $respuestaData['id_pregunta'] = $id;
                    $respuestaData['id_usuario'] = $_SESSION['usuario_id'] ?? 1;
                    $respuestaData['contenido_alternativo'] = '';
                    $respuestaData['es_respuesta_aceptada'] = 1;

                    $respuesta = $modelRespuesta->create($respuestaData);
                    if (!$respuesta) {
                        throw new Exception("Error al guardar la respuesta");
                    }
                }
            }

            // Confirmar transacción
            $model->commit();

            return $this->respondWithJson($response, [
                'success' => true,
                'message' => 'Pregunta actualizada correctamente'
            ]);
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if (isset($model) && method_exists($model, 'rollBack')) {
                $model->rollBack();
            }

            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function obtener($request, $response, $args)
    {
        try {
            $id = $args['id'] ?? null;

            if (!$id) {
                throw new Exception("ID de pregunta no proporcionado");
            }

            // Obtener datos de la pregunta
            $modelPregunta = new TableModel();
            $modelPregunta->setTable("pr_preguntas");
            $modelPregunta->setId("id_pregunta");

            $pregunta = $modelPregunta->select(
                "id_pregunta",
                "titulo",
                "contenido",
                "orden",
                "estado"
            )
                ->where("id_pregunta", $id)
                ->where("eliminado", "0")
                ->first();

            if (!$pregunta) {
                throw new Exception("Pregunta no encontrada");
            }

            // Obtener datos de las respuestas
            $modelRespuesta = new TableModel();
            $modelRespuesta->setTable("pr_respuestas");
            $modelRespuesta->setId("id_respuesta");

            $respuestas = $modelRespuesta->select(
                "id_respuesta",
                "id_tipo_respuesta",
                "contenido",
                "contenido_alternativo",
                "metadatos"
            )
                ->where("id_pregunta", $id)
                ->get();

            // Obtener información del tipo de respuesta
            $tipoRespuesta = null;
            if (!empty($respuestas) && isset($respuestas[0]['id_tipo_respuesta'])) {
                $modelTipoRespuesta = new TableModel();
                $modelTipoRespuesta->setTable("pr_tipo_respuestas");
                $modelTipoRespuesta->setId("id_tipo_respuesta");

                $tipoRespuesta = $modelTipoRespuesta
                    ->select("id_tipo_respuesta", "nombre")
                    ->where("id_tipo_respuesta", $respuestas[0]['id_tipo_respuesta'])
                    ->first();
            }

            // Combinar datos
            $data = $pregunta;

            if (!empty($respuestas)) {
                // Asignar el ID de tipo de respuesta
                $data['id_tipo_respuesta'] = $respuestas[0]['id_tipo_respuesta'];

                // Procesar según el tipo de respuesta
                if ($tipoRespuesta && $tipoRespuesta['nombre'] === 'Escala Likert') {
                    // Para Escala Likert, debemos procesar todas las respuestas
                    if (!empty($respuestas[0]['metadatos'])) {
                        // Tomar los metadatos de la primera respuesta ya que contienen las opciones completas
                        $metadatos = json_decode($respuestas[0]['metadatos'], true);

                        if (isset($metadatos['opciones']) && isset($metadatos['valores'])) {
                            $data['opciones_escala'] = implode(', ', $metadatos['opciones']);
                            $data['valores_escala'] = implode(', ', $metadatos['valores']);
                        }

                        // Incluir los metadatos completos para uso avanzado
                        $data['metadatos_raw'] = $respuestas[0]['metadatos'];
                    }
                } else {
                    // Para otros tipos de respuesta
                    $data['id_respuesta'] = $respuestas[0]['id_respuesta'];
                    $data['contenido_respuesta'] = $respuestas[0]['contenido'];

                    // Procesar metadatos según el tipo
                    if (!empty($respuestas[0]['metadatos'])) {
                        $metadatos = json_decode($respuestas[0]['metadatos'], true);

                        if ($tipoRespuesta) {
                            switch ($tipoRespuesta['nombre']) {
                                case 'Opción múltiple':
                                    if (isset($metadatos['opciones'])) {
                                        $data['opciones'] = implode(', ', $metadatos['opciones']);
                                        // Convertir de base 0 a base 1 para mostrar al usuario
                                        $data['opcion_correcta'] = isset($metadatos['correcta']) ? ($metadatos['correcta'] + 1) : '';
                                    }
                                    break;

                                case 'Selección múltiple':
                                    if (isset($metadatos['opciones'])) {
                                        $data['opciones'] = implode(', ', $metadatos['opciones']);

                                        // Convertir de base 0 a base 1 para mostrar al usuario
                                        if (isset($metadatos['correctas']) && is_array($metadatos['correctas'])) {
                                            $correctasBase1 = array_map(function ($idx) {
                                                return $idx + 1;
                                            }, $metadatos['correctas']);
                                            $data['opciones_correctas'] = implode(', ', $correctasBase1);
                                        }
                                    }
                                    break;

                                case 'Verdadero/Falso':
                                    if (isset($metadatos['correcta'])) {
                                        $data['respuesta_correcta'] = $metadatos['correcta'] ? 'true' : 'false';
                                    }
                                    break;

                                case 'Respuesta corta':
                                    if (isset($metadatos['longitud_maxima'])) {
                                        $data['longitud_maxima'] = $metadatos['longitud_maxima'];
                                    }
                                    if (isset($metadatos['ejemplo'])) {
                                        $data['respuesta_ejemplo'] = $metadatos['ejemplo'];
                                    }
                                    break;

                                case 'Escala':
                                    if (isset($metadatos['min'])) {
                                        $data['valor_minimo'] = $metadatos['min'];
                                    }
                                    if (isset($metadatos['max'])) {
                                        $data['valor_maximo'] = $metadatos['max'];
                                    }
                                    if (isset($metadatos['descripcion'])) {
                                        $data['descripcion_escala'] = $metadatos['descripcion'];
                                    }
                                    break;

                                case 'Desarrollo':
                                    if (isset($metadatos['palabras_minimas'])) {
                                        $data['palabras_minimas'] = $metadatos['palabras_minimas'];
                                    }
                                    if (isset($metadatos['guia'])) {
                                        $data['guia_respuesta'] = $metadatos['guia'];
                                    }
                                    break;
                            }
                        }

                        // Incluir los metadatos completos para uso avanzado
                        $data['metadatos_raw'] = $respuestas[0]['metadatos'];
                    }
                }
            }

            return $this->respondWithJson($response, [
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function eliminar($request, $response, $args)
    {
        try {
            $id = $args['id'] ?? null;

            if (!$id) {
                throw new Exception("ID de pregunta no proporcionado");
            }

            // Iniciar transacción
            // $this->db->beginTransaction();

            // Marcar pregunta como eliminada
            $modelPregunta = new TableModel();
            $modelPregunta->setTable("pr_preguntas");
            $modelPregunta->setId("id_pregunta");

            $eliminarPregunta = $modelPregunta->update($id, [
                'eliminado' => "1",
                'fecha_eliminacion' => date("Y-m-d H:i:s"),
                'eliminado_por' => $_SESSION['app_id'] ?? 1,
            ]);

            if (!$eliminarPregunta) {
                throw new Exception("Error al eliminar la pregunta");
            }

            // Confirmar transacción
            // $this->db->commit();

            return $this->respondWithJson($response, [
                'success' => true,
                'message' => 'Pregunta eliminada correctamente'
            ]);
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            // if (isset($this->db) && $this->db->inTransaction()) {
            //     $this->db->rollBack();
            // }

            return $this->respondWithJson($response, [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
