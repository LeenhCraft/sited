<?php

use App\Models\TableModel;

include_once __DIR__ . "/GenerarNumeros.php";
include_once __DIR__ . "/HelpersCore.php";

function base_url()
{
    return trim($_ENV['APP_URL'], '/') . '/';
}

function  header_web($view, $data = [])
{
    $view = str_replace('.', '/', $view);
    $view_header = "../app/Resources/$view.php";
    require_once $view_header;
}

function footer_web($view, $data = [])
{
    $view = str_replace('.', '/', $view);
    $view_footer =  "../app/Resources/$view.php";
    require_once $view_footer;
}

function getModal($ruta, $data = "")
{
    $view_modal = "../app/Resources/Modals/{$ruta}.php";
    require_once $view_modal;
}

function strClean($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    $string = str_ireplace("//", "", $string);
    $string = str_ireplace("\\", "", $string);
    $string = str_ireplace("'", "", $string);
    return $string;
}

function dep($data, $exit = 0)
{
    $format  = print_r('<pre>');
    $format .= print_r($data);
    $format .= print_r('</pre>');
    ($exit != 0) ? $format .= exit : '';
    return $format;
}

function urls_amigables($url)
{
    // Tranformamos todo a minusculas
    $url = strtolower($url);
    //Rememplazamos caracteres especiales latinos
    $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
    $repl = array('a', 'e', 'i', 'o', 'u', 'n');
    $url = str_replace($find, $repl, $url);
    // Añadimos los guiones
    $find = array(' ', '&', '\r\n', '\n', '+');
    $url = str_replace($find, '-', $url);
    // Eliminamos y Reemplazamos demás caracteres especiales
    $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
    $repl = array('', '-', '');
    $url = preg_replace($find, $repl, $url);
    return $url;
}

function token($cant = 10)
{
    $r1 = bin2hex(random_bytes($cant));
    $r2 = bin2hex(random_bytes($cant));
    $r3 = bin2hex(random_bytes($cant));
    $r4 = bin2hex(random_bytes($cant));
    // $token = $r1 . '-' . $r2 . '-' . $r3 . '-' . $r4;
    $token = $r1 . $r2 . $r3 . $r4;
    return $token;
}

function getPermisosExtras()
{
    $model = new TableModel();
    $model->setTable("sis_permisos_extras");
    $model->setId("idpermiso");
    // Podrías reducir los campos seleccionados solo a los necesarios
    $permisos = $model
        ->select(
            "sis_recursos.identificador as id_recurso",
            "sis_permisos_extras.estado",
            "sis_acciones.identificador as id_accion"
        )
        ->join("sis_recursos", "sis_recursos.idrecurso", "sis_permisos_extras.idrecurso")
        ->join("sis_acciones", "sis_acciones.idaccion", "sis_permisos_extras.idaccion")
        ->where("idrol", $_SESSION["app_r"] ?? '0')
        ->get();
    $resultado = [];
    foreach ($permisos as $permiso) {
        // Si el id_recurso no existe en el resultado, lo inicializamos
        if (!isset($resultado[$permiso['id_recurso']])) {
            $resultado[$permiso['id_recurso']] = [];
        }

        // Agregamos la acción con su estado
        $resultado[$permiso['id_recurso']][$permiso['id_accion']] = (int)$permiso['estado'];
    }
    return $resultado;
}
