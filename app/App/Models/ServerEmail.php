<?php

namespace App\Models;

class ServerEmail extends Model
{
    protected $table = "sis_server_email";
    protected $id = "idserveremail ";

    public function leerConfig()
    {
        return $this->where("em_default", "1")->where("em_estado", "1")->first();
    }
}
