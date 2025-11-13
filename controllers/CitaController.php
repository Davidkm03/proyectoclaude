<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Cita.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'listar':
        listarCitas();
        break;
    case 'crear':
        crearCita();
        break;
    case 'actualizar':
        actualizarCita();
        break;
    case 'eliminar':
        eliminarCita();
        break;
    case 'obtener':
        obtenerCita();
        break;
    case 'buscar':
        buscarCitas();
        break;
    case 'filtrar':
        filtrarCitas();
        break;
    case 'actualizarDiagnostico':
        actualizarDiagnostico();
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
}

function listarCitas() {
    $citas = leerJSON(CITAS_FILE);
    $pacientes = leerJSON(PACIENTES_FILE);
    $doctores = leerJSON(DOCTORES_FILE);

    // Enriquecer citas con nombres de pacientes y doctores
    foreach ($citas as &$cita) {
        foreach ($pacientes as $pac) {
            if ($pac['IDPaciente'] === $cita['IDPaciente']) {
                $cita['NombrePaciente'] = $pac['NombreCompleto'];
                break;
            }
        }
        foreach ($doctores as $doc) {
            if ($doc['IDDoctor'] === $cita['IDDoctor']) {
                $cita['NombreDoctor'] = $doc['Nombre'];
                break;
            }
        }
    }

    echo json_encode(['success' => true, 'data' => $citas]);
}

function crearCita() {
    $datos = [
        'IDPaciente' => sanitizar($_POST['IDPaciente'] ?? ''),
        'IDDoctor' => sanitizar($_POST['IDDoctor'] ?? ''),
        'Fecha' => sanitizar($_POST['Fecha'] ?? ''),
        'Hora' => sanitizar($_POST['Hora'] ?? ''),
        'NotasDiagnostico' => sanitizar($_POST['NotasDiagnostico'] ?? ''),
        'Estado' => sanitizar($_POST['Estado'] ?? 'Programada')
    ];

    $cita = new Cita($datos);
    $errores = $cita->validar();

    if (!empty($errores)) {
        echo json_encode(['success' => false, 'errors' => $errores]);
        return;
    }

    // Verificar que el paciente existe
    $pacientes = leerJSON(PACIENTES_FILE);
    $pacienteExiste = false;
    foreach ($pacientes as $pac) {
        if ($pac['IDPaciente'] === $cita->IDPaciente) {
            $pacienteExiste = true;
            break;
        }
    }

    if (!$pacienteExiste) {
        echo json_encode(['success' => false, 'errors' => ['Paciente no encontrado']]);
        return;
    }

    // Verificar que el doctor existe
    $doctores = leerJSON(DOCTORES_FILE);
    $doctorExiste = false;
    foreach ($doctores as $doc) {
        if ($doc['IDDoctor'] === $cita->IDDoctor) {
            $doctorExiste = true;
            break;
        }
    }

    if (!$doctorExiste) {
        echo json_encode(['success' => false, 'errors' => ['Doctor no encontrado']]);
        return;
    }

    // Verificar conflicto de horario
    $citas = leerJSON(CITAS_FILE);
    foreach ($citas as $c) {
        if ($c['IDDoctor'] === $cita->IDDoctor &&
            $c['Fecha'] === $cita->Fecha &&
            $c['Hora'] === $cita->Hora &&
            $c['Estado'] === 'Programada') {
            echo json_encode(['success' => false, 'errors' => ['El doctor ya tiene una cita programada en ese horario']]);
            return;
        }
    }

    $citas[] = $cita->toArray();

    if (escribirJSON(CITAS_FILE, $citas)) {
        echo json_encode(['success' => true, 'message' => 'Cita creada exitosamente', 'data' => $cita->toArray()]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
    }
}

function actualizarCita() {
    $id = sanitizar($_POST['IDCita'] ?? '');
    $citas = leerJSON(CITAS_FILE);
    $encontrado = false;

    foreach ($citas as $key => $cit) {
        if ($cit['IDCita'] === $id) {
            $datos = [
                'IDCita' => $id,
                'IDPaciente' => sanitizar($_POST['IDPaciente'] ?? ''),
                'IDDoctor' => sanitizar($_POST['IDDoctor'] ?? ''),
                'Fecha' => sanitizar($_POST['Fecha'] ?? ''),
                'Hora' => sanitizar($_POST['Hora'] ?? ''),
                'NotasDiagnostico' => sanitizar($_POST['NotasDiagnostico'] ?? ''),
                'Estado' => sanitizar($_POST['Estado'] ?? 'Programada')
            ];

            $cita = new Cita($datos);
            $errores = $cita->validar();

            if (!empty($errores)) {
                echo json_encode(['success' => false, 'errors' => $errores]);
                return;
            }

            $citas[$key] = $cita->toArray();
            $encontrado = true;
            break;
        }
    }

    if ($encontrado) {
        if (escribirJSON(CITAS_FILE, $citas)) {
            echo json_encode(['success' => true, 'message' => 'Cita actualizada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => ['Cita no encontrada']]);
    }
}

function actualizarDiagnostico() {
    $id = sanitizar($_POST['IDCita'] ?? '');
    $notas = sanitizar($_POST['NotasDiagnostico'] ?? '');
    $estado = sanitizar($_POST['Estado'] ?? 'Completada');

    $citas = leerJSON(CITAS_FILE);
    $encontrado = false;

    foreach ($citas as $key => $cit) {
        if ($cit['IDCita'] === $id) {
            $citas[$key]['NotasDiagnostico'] = $notas;
            $citas[$key]['Estado'] = $estado;
            $encontrado = true;
            break;
        }
    }

    if ($encontrado) {
        if (escribirJSON(CITAS_FILE, $citas)) {
            echo json_encode(['success' => true, 'message' => 'Diagnóstico actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => ['Cita no encontrada']]);
    }
}

function eliminarCita() {
    $id = sanitizar($_POST['IDCita'] ?? $_GET['id'] ?? '');
    $citas = leerJSON(CITAS_FILE);
    $nuevaLista = [];

    foreach ($citas as $cit) {
        if ($cit['IDCita'] !== $id) {
            $nuevaLista[] = $cit;
        }
    }

    if (count($nuevaLista) < count($citas)) {
        if (escribirJSON(CITAS_FILE, $nuevaLista)) {
            echo json_encode(['success' => true, 'message' => 'Cita eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'errors' => ['Error al guardar el archivo']]);
        }
    } else {
        echo json_encode(['success' => false, 'errors' => ['Cita no encontrada']]);
    }
}

function obtenerCita() {
    $id = sanitizar($_GET['id'] ?? '');
    $citas = leerJSON(CITAS_FILE);

    foreach ($citas as $cit) {
        if ($cit['IDCita'] === $id) {
            echo json_encode(['success' => true, 'data' => $cit]);
            return;
        }
    }

    echo json_encode(['success' => false, 'errors' => ['Cita no encontrada']]);
}

function filtrarCitas() {
    $fechaInicio = sanitizar($_GET['fechaInicio'] ?? '');
    $fechaFin = sanitizar($_GET['fechaFin'] ?? '');
    $idDoctor = sanitizar($_GET['idDoctor'] ?? '');
    $estado = sanitizar($_GET['estado'] ?? '');

    $citas = leerJSON(CITAS_FILE);
    $resultados = [];

    foreach ($citas as $cita) {
        $incluir = true;

        if (!empty($fechaInicio) && $cita['Fecha'] < $fechaInicio) {
            $incluir = false;
        }

        if (!empty($fechaFin) && $cita['Fecha'] > $fechaFin) {
            $incluir = false;
        }

        if (!empty($idDoctor) && $cita['IDDoctor'] !== $idDoctor) {
            $incluir = false;
        }

        if (!empty($estado) && $cita['Estado'] !== $estado) {
            $incluir = false;
        }

        if ($incluir) {
            $resultados[] = $cita;
        }
    }

    // Enriquecer con nombres
    $pacientes = leerJSON(PACIENTES_FILE);
    $doctores = leerJSON(DOCTORES_FILE);

    foreach ($resultados as &$cita) {
        foreach ($pacientes as $pac) {
            if ($pac['IDPaciente'] === $cita['IDPaciente']) {
                $cita['NombrePaciente'] = $pac['NombreCompleto'];
                break;
            }
        }
        foreach ($doctores as $doc) {
            if ($doc['IDDoctor'] === $cita['IDDoctor']) {
                $cita['NombreDoctor'] = $doc['Nombre'];
                break;
            }
        }
    }

    echo json_encode(['success' => true, 'data' => $resultados]);
}

function buscarCitas() {
    $termino = strtolower(sanitizar($_GET['termino'] ?? ''));
    $citas = leerJSON(CITAS_FILE);
    $resultados = [];

    foreach ($citas as $cit) {
        if (strpos(strtolower($cit['IDCita']), $termino) !== false ||
            strpos(strtolower($cit['Fecha']), $termino) !== false ||
            strpos(strtolower($cit['Estado']), $termino) !== false) {
            $resultados[] = $cit;
        }
    }

    echo json_encode(['success' => true, 'data' => $resultados]);
}
?>
