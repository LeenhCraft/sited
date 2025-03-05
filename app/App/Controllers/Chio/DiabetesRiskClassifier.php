<?php

namespace App\Controllers\Chio;

use Exception;

class DiabetesRiskClassifier
{
    /**
     * Array de entradas con valores 0, 1, o 2 para cada uno de los 13 factores
     * [0] => Edad: 0 (Menor de 45), 1 (Entre 45-54), 2 (Mayor a 54)
     * [1] => IMC: 0 (Menor a 25), 1 (Entre 25-30), 2 (Mayor a 30)
     * [2] => Ansiedad/Estrés: 0 (Casi nunca), 1 (A veces), 2 (Casi siempre) 
     * [3] => Consumo grasas: 0 (Nada), 1 (Poco), 2 (Mucho)
     * [4] => Sed/hambre: 0 (Nada), 1 (Poco), 2 (Mucho)
     * [5] => Antecedentes glucosa: 0 (No), 2 (Sí)
     * [6] => Visión borrosa: 0 (No), 1 (Ocasionalmente), 2 (Siempre)
     * [7] => Cicatrización lenta: 0 (No), 1 (Ocasionalmente), 2 (Siempre)
     * [8] => Cansancio: 0 (Nada), 1 (Poco), 2 (Mucho)
     * [9] => Hormigueo: 0 (Nada), 1 (Poco), 2 (Mucho)
     * [10] => Actividad física: 0 (Nada), 1 (Poco), 2 (Mucho)
     * [11] => Frutas/verduras: 0 (No), 1 (Regularmente), 2 (Sí)
     * [12] => Antecedentes familiares: 0 (No), 1 (Segundo grado), 2 (Primer grado)
     */
    private $entradas = [];

    // Resultados del análisis
    private $count_0 = 0;
    private $count_1 = 0;
    private $count_2 = 0;
    private $total = 0;
    private $p0 = 0;
    private $p1 = 0;
    private $p2 = 0;

    /**
     * Constructor que recibe los valores de entrada
     * 
     * @param array $entradas Array con 13 valores (0, 1 o 2)
     */
    public function __construct(array $entradas = [])
    {
        if (!empty($entradas)) {
            $this->setEntradas($entradas);
        }
    }

    /**
     * Establece los valores de entrada para el clasificador
     * 
     * @param array $entradas Array con 13 valores (0, 1 o 2)
     * @return DiabetesRiskClassifier
     */
    public function setEntradas(array $entradas)
    {
        // Validar que tengamos 13 entradas
        if (count($entradas) != 13) {
            throw new Exception("Se requieren exactamente 13 valores de entrada");
        }

        // Validar que todos sean 0, 1 o 2
        foreach ($entradas as $valor) {
            if (!in_array($valor, [0, 1, 2])) {
                throw new Exception("Todos los valores deben ser 0, 1 o 2");
            }
        }

        $this->entradas = $entradas;
        return $this;
    }

    /**
     * Cuenta las valoraciones y calcula proporciones
     * 
     * @return DiabetesRiskClassifier
     */
    public function contarValoraciones()
    {
        // Contar ocurrencias
        $this->count_0 = 0;
        $this->count_1 = 0;
        $this->count_2 = 0;

        foreach ($this->entradas as $valor) {
            if ($valor === 0) $this->count_0++;
            elseif ($valor === 1) $this->count_1++;
            elseif ($valor === 2) $this->count_2++;
        }

        $this->total = count($this->entradas);

        // Calcular proporciones
        $this->p0 = $this->count_0 / $this->total;
        $this->p1 = $this->count_1 / $this->total;
        $this->p2 = $this->count_2 / $this->total;

        return $this;
    }

    /**
     * Determina el resultado basado en las proporciones
     * 
     * @return array Resultado ['clasificacion', 'p_bajo', 'p_moderado', 'p_alto']
     */
    public function determinarResultado()
    {
        // Verificar que se han contado las valoraciones
        if ($this->total === 0) {
            $this->contarValoraciones();
        }

        // Convertir a porcentajes
        $p_bajo = $this->p0 * 100;
        $p_moderado = $this->p1 * 100;
        $p_alto = $this->p2 * 100;

        // Tolerancia para empates
        $epsilon = 1e-10;

        // Determinar clasificación
        if (abs($this->p0 - $this->p1) < $epsilon && abs($this->p0 - $this->p2) < $epsilon) {
            $resultado = "Bajo/Moderado/Alto";
        } elseif (abs($this->p0 - $this->p1) < $epsilon && $this->p0 > $this->p2) {
            $resultado = "Bajo/Moderado";
        } elseif (abs($this->p0 - $this->p2) < $epsilon && $this->p0 > $this->p1) {
            $resultado = "Bajo/Alto";
        } elseif (abs($this->p1 - $this->p2) < $epsilon && $this->p1 > $this->p0) {
            $resultado = "Moderado/Alto";
        } elseif ($this->p0 > $this->p1 && $this->p0 > $this->p2) {
            $resultado = "Bajo";
        } elseif ($this->p1 > $this->p0 && $this->p1 > $this->p2) {
            $resultado = "Moderado";
        } elseif ($this->p2 > $this->p0 && $this->p2 > $this->p1) {
            $resultado = "Alto";
        } else {
            $resultado = "Indeterminado";
        }

        return [
            'clasificacion' => $resultado,
            'p_bajo' => $p_bajo,
            'p_moderado' => $p_moderado,
            'p_alto' => $p_alto
        ];
    }

    /**
     * Genera recomendaciones basadas en el resultado
     * 
     * @param string $clasificacion Resultado de la clasificación
     * @return array Array de recomendaciones
     */
    public function obtenerRecomendaciones($clasificacion)
    {
        $recomendaciones = [];

        if (strpos($clasificacion, "Bajo") !== false && strpos($clasificacion, "/") === false) {
            $recomendaciones[] = "Continuar con hábitos saludables";
            $recomendaciones[] = "Monitoreo rutinario de glucosa en chequeos anuales";
        } elseif (strpos($clasificacion, "Moderado") !== false && strpos($clasificacion, "/") === false) {
            $recomendaciones[] = "Consulta médica para evaluación preventiva";
            $recomendaciones[] = "Revisar hábitos alimenticios y actividad física";
            $recomendaciones[] = "Considerar pruebas específicas de glucosa";
        } elseif (strpos($clasificacion, "Alto") !== false && strpos($clasificacion, "/") === false) {
            $recomendaciones[] = "Consulta médica inmediata";
            $recomendaciones[] = "Pruebas de laboratorio para evaluar niveles de glucosa";
            $recomendaciones[] = "Posible derivación a especialista en endocrinología";
        } else {
            // Caso de empate
            $categorias = explode('/', $clasificacion);

            foreach ($categorias as $categoria) {
                if ($categoria === "Bajo") {
                    $recomendaciones['bajo'] = [
                        "Continuar con hábitos saludables",
                        "Monitoreo rutinario de glucosa en chequeos anuales"
                    ];
                }
                if ($categoria === "Moderado") {
                    $recomendaciones['moderado'] = [
                        "Consulta médica para evaluación preventiva",
                        "Revisar hábitos alimenticios y actividad física",
                        "Considerar pruebas específicas de glucosa"
                    ];
                }
                if ($categoria === "Alto") {
                    $recomendaciones['alto'] = [
                        "Consulta médica inmediata",
                        "Pruebas de laboratorio para evaluar niveles de glucosa",
                        "Posible derivación a especialista en endocrinología"
                    ];
                }
            }
        }

        return $recomendaciones;
    }

    /**
     * Analiza las entradas y genera un resultado completo
     * 
     * @return array Resultado completo del análisis
     */
    public function analizar()
    {
        $this->contarValoraciones();
        $resultado = $this->determinarResultado();
        $recomendaciones = $this->obtenerRecomendaciones($resultado['clasificacion']);

        return [
            'entradas' => $this->entradas,
            'conteo' => [
                'bajo' => $this->count_0,
                'moderado' => $this->count_1,
                'alto' => $this->count_2,
                'total' => $this->total
            ],
            'probabilidades' => [
                'bajo' => $resultado['p_bajo'],
                'moderado' => $resultado['p_moderado'],
                'alto' => $resultado['p_alto']
            ],
            'clasificacion' => $resultado['clasificacion'],
            'recomendaciones' => $recomendaciones
        ];
    }

    /**
     * Procesa los datos de un formulario para crear las entradas
     * 
     * @param array $formData Datos del formulario
     * @return array Array de 13 valores (0, 1, 2) para usar en setEntradas
     */
    public static function procesarFormulario($formData)
    {
        $entradas = [];

        // Edad
        if ($formData['edad'] < 45) {
            $entradas[] = 0;
        } elseif ($formData['edad'] >= 45 && $formData['edad'] <= 54) {
            $entradas[] = 1;
        } else {
            $entradas[] = 2;
        }

        // IMC
        if ($formData['imc'] < 25) {
            $entradas[] = 0;
        } elseif ($formData['imc'] >= 25 && $formData['imc'] <= 30) {
            $entradas[] = 1;
        } else {
            $entradas[] = 2;
        }

        // Añadir el resto de valores directamente si ya están codificados como 0,1,2
        $entradas[] = $formData['ansiedad'];
        $entradas[] = $formData['grasas'];
        $entradas[] = $formData['sed_hambre'];
        $entradas[] = $formData['glucosa'];
        $entradas[] = $formData['vision'];
        $entradas[] = $formData['cicatrizacion'];
        $entradas[] = $formData['cansancio'];
        $entradas[] = $formData['hormigueo'];
        $entradas[] = $formData['actividad'];
        $entradas[] = $formData['frutas_verduras'];
        $entradas[] = $formData['antecedentes'];

        return $entradas;
    }
}
