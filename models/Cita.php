<?php
class Cita {
    public $IDCita;
    public $IDPaciente;
    public $IDDoctor;
    public $Fecha;
    public $Hora;
    public $NotasDiagnostico;
    public $Estado;

    public function __construct($datos = []) {
        $this->IDCita = $datos['IDCita'] ?? generarID('CIT-');
        $this->IDPaciente = $datos['IDPaciente'] ?? '';
        $this->IDDoctor = $datos['IDDoctor'] ?? '';
        $this->Fecha = $datos['Fecha'] ?? '';
        $this->Hora = $datos['Hora'] ?? '';
        $this->NotasDiagnostico = $datos['NotasDiagnostico'] ?? '';
        $this->Estado = $datos['Estado'] ?? 'Programada';
    }

    public function validar() {
        $errores = [];

        if (empty($this->IDPaciente)) {
            $errores[] = "Debe seleccionar un paciente";
        }

        if (empty($this->IDDoctor)) {
            $errores[] = "Debe seleccionar un doctor";
        }

        if (!validarFecha($this->Fecha)) {
            $errores[] = "Fecha inválida (formato: YYYY-MM-DD)";
        } else {
            $fechaCita = new DateTime($this->Fecha);
            $hoy = new DateTime();
            $hoy->setTime(0, 0, 0);

            if ($fechaCita < $hoy) {
                $errores[] = "La fecha de la cita no puede ser anterior a hoy";
            }
        }

        if (!preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $this->Hora)) {
            $errores[] = "Hora inválida (formato: HH:MM)";
        }

        if (!in_array($this->Estado, ['Programada', 'Completada', 'Cancelada'])) {
            $errores[] = "Estado inválido";
        }

        return $errores;
    }

    public function toArray() {
        return [
            'IDCita' => $this->IDCita,
            'IDPaciente' => $this->IDPaciente,
            'IDDoctor' => $this->IDDoctor,
            'Fecha' => $this->Fecha,
            'Hora' => $this->Hora,
            'NotasDiagnostico' => $this->NotasDiagnostico,
            'Estado' => $this->Estado
        ];
    }
}
?>
