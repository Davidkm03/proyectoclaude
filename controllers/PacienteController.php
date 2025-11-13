<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Paciente.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'listar':
        listarPacientes();
        break;
    case 'crear':
        crearPaciente();
        break;
    case 'actualizar':
        actualizarPaciente();
        break;
    case 'eliminar':
        eliminarPaciente();
        break;
    case 'obtener':
        obtenerPaciente();
        break;
    case 'buscar':
        buscarPacientes();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarPacientes() {
    $pacientes = leerJSON(PACIENTES_FILE);
    echo json_encode(['success' => true, 'data' => $pacientes]);
}

function crearPaciente() {
    $datos = [
        'NombreCompleto' => sanitizar($_POST['NombreCompleto'] ?? ''),
        'Edad' => (int)($_POST['Edad'] ?? 0),
        'Genero' => sanitizar($_POST['Genero'] ?? ''),
        'TipoSanguineo' => sanitizar($_POST['TipoSanguineo'] ?? ''),
        'Alergias' => sanitizar($_POST['Alergias'] ?? 'Ninguna'),
        'ContactoEmergencia' => sanitizar($_POST['ContactoEmergencia'] ?? '')
    ];

    $paciente = new Paciente($datos);
    $errores = $paciente->validar();

    if (!empty($errores)) {
        echo json_encode(['success' => false, 'errors' => $errores]);
        return;
    }

    $pacientes = leerJSON(PACIENTES_FILE);
    $pacientes[] = $paciente->toArray();

    if (escribirJSON(PACIENTES_FILE, $pacientes)) {
        echo json_encode(['success' => true, 'message' => 'Paciente creado exitosamente', 'data' => $paciente->toArray()]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
    }
}

function actualizarPaciente() {
    $id = sanitizar($_POST['IDPaciente'] ?? '');
    $pacientes = leerJSON(PACIENTES_FILE);
    $encontrado = false;

    foreach ($pacientes as $key => $pac) {
        if ($pac['IDPaciente'] === $id) {
            $datos = [
                'IDPaciente' => $id,
                'NombreCompleto' => sanitizar($_POST['NombreCompleto'] ?? ''),
                'Edad' => (int)($_POST['Edad'] ?? 0),
                'Genero' => sanitizar($_POST['Genero'] ?? ''),
                'TipoSanguineo' => sanitizar($_POST['TipoSanguineo'] ?? ''),
                'Alergias' => sanitizar($_POST['Alergias'] ?? 'Ninguna'),
                'ContactoEmergencia' => sanitizar($_POST['ContactoEmergencia'] ?? '')
            ];

            $paciente = new Paciente($datos);
            $errores = $paciente->validar();

            if (!empty($errores)) {
                echo json_encode(['success' => false, 'errors' => $errores]);
                return;
            }

            $pacientes[$key] = $paciente->toArray();
            $encontrado = true;
            break;
        }
    }

    if ($encontrado) {
        if (escribirJSON(PACIENTES_FILE, $pacientes)) {
            echo json_encode(['success' => true, 'message' => 'Paciente actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => ['Paciente no encontrado']]);
    }
}

function eliminarPaciente() {
    $id = sanitizar($_POST['IDPaciente'] ?? $_GET['id'] ?? '');
    $pacientes = leerJSON(PACIENTES_FILE);
    $nuevaLista = [];

    foreach ($pacientes as $pac) {
        if ($pac['IDPaciente'] !== $id) {
            $nuevaLista[] = $pac;
        }
    }

    if (count($nuevaLista) < count($pacientes)) {
        // Verificar si el paciente tiene citas
        $citas = leerJSON(CITAS_FILE);
        foreach ($citas as $cita) {
            if ($cita['IDPaciente'] === $id && $cita['Estado'] === 'Programada') {
                echo json_encode(['success' => false, 'errors' => ['No se puede eliminar: el paciente tiene citas programadas']]);
                return;
            }
        }

        if (escribirJSON(PACIENTES_FILE, $nuevaLista)) {
            echo json_encode(['success' => true, 'message' => 'Paciente eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => ['Paciente no encontrado']]);
    }
}

function obtenerPaciente() {
    $id = sanitizar($_GET['id'] ?? '');
    $pacientes = leerJSON(PACIENTES_FILE);

    foreach ($pacientes as $pac) {
        if ($pac['IDPaciente'] === $id) {
            echo json_encode(['success' => true, 'data' => $pac]);
            return;
        }
    }

    echo json_encode(['success' => false, 'errors' => ['Paciente no encontrado']]);
}

function buscarPacientes() {
    $termino = strtolower(sanitizar($_GET['termino'] ?? ''));
    $pacientes = leerJSON(PACIENTES_FILE);
    $resultados = [];

    foreach ($pacientes as $pac) {
        if (strpos(strtolower($pac['NombreCompleto']), $termino) !== false ||
            strpos(strtolower($pac['IDPaciente']), $termino) !== false ||
            strpos(strtolower($pac['TipoSanguineo']), $termino) !== false) {
            $resultados[] = $pac;
        }
    }

    echo json_encode(['success' => true, 'data' => $resultados]);
}
?>
