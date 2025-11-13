<?php
// Configuración del sistema

// Rutas de archivos de datos
define('DATA_PATH', __DIR__ . '/../data/');
define('PACIENTES_FILE', DATA_PATH . 'pacientes.json');
define('DOCTORES_FILE', DATA_PATH . 'doctores.json');
define('CITAS_FILE', DATA_PATH . 'citas.json');

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Gestión Hospitalaria');
define('APP_VERSION', '1.0.0');
define('APP_AUTHOR', 'Tu Nombre');

// Zona horaria
date_default_timezone_set('America/Santo_Domingo');

// Función para inicializar archivos de datos
function inicializarArchivos() {
    $archivos = [
        PACIENTES_FILE => [],
        DOCTORES_FILE => [],
        CITAS_FILE => []
    ];

    foreach ($archivos as $archivo => $contenidoInicial) {
        if (!file_exists($archivo)) {
            $dir = dirname($archivo);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            file_put_contents($archivo, json_encode($contenidoInicial, JSON_PRETTY_PRINT));
        }
    }
}

// Función para leer archivo JSON
function leerJSON($archivo) {
    if (!file_exists($archivo)) {
        return [];
    }
    $contenido = file_get_contents($archivo);
    return json_decode($contenido, true) ?? [];
}

// Función para escribir archivo JSON
function escribirJSON($archivo, $datos) {
    return file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Función para generar ID único
function generarID($prefijo = '') {
    return $prefijo . uniqid() . rand(1000, 9999);
}

// Función para validar fecha
function validarFecha($fecha) {
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    return $d && $d->format('Y-m-d') === $fecha;
}

// Función para validar email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Función para sanitizar entrada
function sanitizar($dato) {
    return htmlspecialchars(strip_tags(trim($dato)));
}

// Inicializar archivos al incluir el config
inicializarArchivos();
?>
