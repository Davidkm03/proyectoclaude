<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Doctor.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'listar':
        listarDoctores();
        break;
    case 'crear':
        crearDoctor();
        break;
    case 'actualizar':
        actualizarDoctor();
        break;
    case 'eliminar':
        eliminarDoctor();
        break;
    case 'obtener':
        obtenerDoctor();
        break;
    case 'buscar':
        buscarDoctores();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarDoctores() {
    $doctores = leerJSON(DOCTORES_FILE);
    echo json_encode(['success' => true, 'data' => $doctores]);
}

function crearDoctor() {
    $datos = [
        'Nombre' => sanitizar($_POST['Nombre'] ?? ''),
        'Especializacion' => sanitizar($_POST['Especializacion'] ?? ''),
        'TelefonoContacto' => sanitizar($_POST['TelefonoContacto'] ?? ''),
        'Email' => sanitizar($_POST['Email'] ?? ''),
        'Turno' => sanitizar($_POST['Turno'] ?? 'Mañana'),
        'PacientesActivos' => (int)($_POST['PacientesActivos'] ?? 0)
    ];

    $doctor = new Doctor($datos);
    $errores = $doctor->validar();

    if (!empty($errores)) {
        echo json_encode(['success' => false, 'errors' => $errores]);
        return;
    }

    $doctores = leerJSON(DOCTORES_FILE);

    // Verificar email duplicado
    foreach ($doctores as $doc) {
        if ($doc['Email'] === $doctor->Email) {
            echo json_encode(['success' => false, 'errors' => ['El email ya está registrado']]);
            return;
        }
    }

    $doctores[] = $doctor->toArray();

    if (escribirJSON(DOCTORES_FILE, $doctores)) {
        echo json_encode(['success' => true, 'message' => 'Doctor creado exitosamente', 'data' => $doctor->toArray()]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
    }
}

function actualizarDoctor() {
    $id = sanitizar($_POST['IDDoctor'] ?? '');
    $doctores = leerJSON(DOCTORES_FILE);
    $encontrado = false;

    foreach ($doctores as $key => $doc) {
        if ($doc['IDDoctor'] === $id) {
            $datos = [
                'IDDoctor' => $id,
                'Nombre' => sanitizar($_POST['Nombre'] ?? ''),
                'Especializacion' => sanitizar($_POST['Especializacion'] ?? ''),
                'TelefonoContacto' => sanitizar($_POST['TelefonoContacto'] ?? ''),
                'Email' => sanitizar($_POST['Email'] ?? ''),
                'Turno' => sanitizar($_POST['Turno'] ?? 'Mañana'),
                'PacientesActivos' => (int)($_POST['PacientesActivos'] ?? 0)
            ];

            $doctor = new Doctor($datos);
            $errores = $doctor->validar();

            if (!empty($errores)) {
                echo json_encode(['success' => false, 'errors' => $errores]);
                return;
            }

            // Verificar email duplicado (excepto el mismo doctor)
            foreach ($doctores as $d) {
                if ($d['Email'] === $doctor->Email && $d['IDDoctor'] !== $id) {
                    echo json_encode(['success' => false, 'errors' => ['El email ya está registrado']]);
                    return;
                }
            }

            $doctores[$key] = $doctor->toArray();
            $encontrado = true;
            break;
        }
    }

    if ($encontrado) {
        if (escribirJSON(DOCTORES_FILE, $doctores)) {
            echo json_encode(['success' => true, 'message' => 'Doctor actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => ['Doctor no encontrado']]);
    }
}

function eliminarDoctor() {
    $id = sanitizar($_POST['IDDoctor'] ?? $_GET['id'] ?? '');
    $doctores = leerJSON(DOCTORES_FILE);
    $nuevaLista = [];

    foreach ($doctores as $doc) {
        if ($doc['IDDoctor'] !== $id) {
            $nuevaLista[] = $doc;
        }
    }

    if (count($nuevaLista) < count($doctores)) {
        // Verificar si el doctor tiene citas
        $citas = leerJSON(CITAS_FILE);
        foreach ($citas as $cita) {
            if ($cita['IDDoctor'] === $id && $cita['Estado'] === 'Programada') {
                echo json_encode(['success' => false, 'errors' => ['No se puede eliminar: el doctor tiene citas programadas']]);
                return;
            }
        }

        if (escribirJSON(DOCTORES_FILE, $nuevaLista)) {
            echo json_encode(['success' => true, 'message' => 'Doctor eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => ['Doctor no encontrado']]);
    }
}

function obtenerDoctor() {
    $id = sanitizar($_GET['id'] ?? '');
    $doctores = leerJSON(DOCTORES_FILE);

    foreach ($doctores as $doc) {
        if ($doc['IDDoctor'] === $id) {
            echo json_encode(['success' => true, 'data' => $doc]);
            return;
        }
    }

    echo json_encode(['success' => false, 'errors' => ['Doctor no encontrado']]);
}

function buscarDoctores() {
    $termino = strtolower(sanitizar($_GET['termino'] ?? ''));
    $doctores = leerJSON(DOCTORES_FILE);
    $resultados = [];

    foreach ($doctores as $doc) {
        if (strpos(strtolower($doc['Nombre']), $termino) !== false ||
            strpos(strtolower($doc['Especializacion']), $termino) !== false ||
            strpos(strtolower($doc['IDDoctor']), $termino) !== false) {
            $resultados[] = $doc;
        }
    }

    echo json_encode(['success' => true, 'data' => $resultados]);
}
?>
