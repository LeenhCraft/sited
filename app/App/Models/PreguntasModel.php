<?php

namespace App\Models;

use App\Models\TableModel;

class PreguntasModel extends TableModel
{
    protected $table = 'pr_preguntas';
    protected $id = 'id_pregunta';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene todas las preguntas activas ordenadas por el campo 'orden'
     */
    public function obtenerPreguntasActivas()
    {
        return $this->select(
            'id_pregunta',
            'orden',
            'titulo',
            'contenido'
        )
            ->where('estado', 'Activo')
            ->where('eliminado', "0")
            ->orderBy('orden', 'ASC')
            ->get();
    }

    /**
     * Cuenta el número total de preguntas activas
     */
    public function contarPreguntasActivas()
    {
        $result = $this->select('COUNT(*) as total')
            ->where('estado', 'Activo')
            ->where('eliminado', "0")
            ->first();
        return $result['total'];
    }

    /**
     * Obtiene una pregunta específica por su ID
     */
    public function obtenerPreguntaPorId($id)
    {
        return $this->select(
            'id_pregunta',
            'orden',
            'titulo',
            'contenido'
        )
            ->where('id_pregunta', $id)
            ->where('estado', 'Activo')
            ->where('eliminado', "0")
            ->first();
    }
}
