<?php
class Doctor {
    public $IDDoctor;
    public $Nombre;
    public $Especializacion;
    public $TelefonoContacto;
    public $Email;
    public $Turno;
    public $PacientesActivos;

    public function __construct($datos = []) {
        $this->IDDoctor = $datos['IDDoctor'] ?? generarID('DOC-');
        $this->Nombre = $datos['Nombre'] ?? '';
        $this->Especializacion = $datos['Especializacion'] ?? '';
        $this->TelefonoContacto = $datos['TelefonoContacto'] ?? '';
        $this->Email = $datos['Email'] ?? '';
        $this->Turno = $datos['Turno'] ?? 'Mañana';
        $this->PacientesActivos = $datos['PacientesActivos'] ?? 0;
    }

    public function validar() {
        $errores = [];

        if (empty($this->Nombre) || strlen($this->Nombre) < 3) {
            $errores[] = "El nombre debe tener al menos 3 caracteres";
        }

        if (empty($this->Especializacion)) {
            $errores[] = "La especialización es requerida";
        }

        if (empty($this->TelefonoContacto) || !preg_match('/^[0-9\-\+\(\)\s]+$/', $this->TelefonoContacto)) {
            $errores[] = "Teléfono de contacto inválido";
        }

        if (!validarEmail($this->Email)) {
            $errores[] = "Email inválido";
        }

        if (!in_array($this->Turno, ['Mañana', 'Tarde', 'Noche'])) {
            $errores[] = "Turno inválido (debe ser Mañana, Tarde o Noche)";
        }

        if (!is_numeric($this->PacientesActivos) || $this->PacientesActivos < 0) {
            $errores[] = "Pacientes activos debe ser un número positivo";
        }

        return $errores;
    }

    public function toArray() {
        return [
            'IDDoctor' => $this->IDDoctor,
            'Nombre' => $this->Nombre,
            'Especializacion' => $this->Especializacion,
            'TelefonoContacto' => $this->TelefonoContacto,
            'Email' => $this->Email,
            'Turno' => $this->Turno,
            'PacientesActivos' => (int)$this->PacientesActivos
        ];
    }
}
?>
