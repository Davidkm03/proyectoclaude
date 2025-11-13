<?php
class Paciente {
    public $IDPaciente;
    public $NombreCompleto;
    public $Edad;
    public $Genero;
    public $TipoSanguineo;
    public $Alergias;
    public $ContactoEmergencia;

    public function __construct($datos = []) {
        $this->IDPaciente = $datos['IDPaciente'] ?? generarID('PAC-');
        $this->NombreCompleto = $datos['NombreCompleto'] ?? '';
        $this->Edad = $datos['Edad'] ?? 0;
        $this->Genero = $datos['Genero'] ?? '';
        $this->TipoSanguineo = $datos['TipoSanguineo'] ?? '';
        $this->Alergias = $datos['Alergias'] ?? 'Ninguna';
        $this->ContactoEmergencia = $datos['ContactoEmergencia'] ?? '';
    }

    public function validar() {
        $errores = [];

        if (empty($this->NombreCompleto) || strlen($this->NombreCompleto) < 3) {
            $errores[] = "El nombre completo debe tener al menos 3 caracteres";
        }

        if (!is_numeric($this->Edad) || $this->Edad < 0 || $this->Edad > 150) {
            $errores[] = "La edad debe ser un número entre 0 y 150";
        }

        if (!in_array($this->Genero, ['Masculino', 'Femenino', 'Otro'])) {
            $errores[] = "Género inválido";
        }

        $tiposSanguineos = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        if (!in_array($this->TipoSanguineo, $tiposSanguineos)) {
            $errores[] = "Tipo sanguíneo inválido";
        }

        if (empty($this->ContactoEmergencia) || !preg_match('/^[0-9\-\+\(\)\s]+$/', $this->ContactoEmergencia)) {
            $errores[] = "Contacto de emergencia inválido";
        }

        return $errores;
    }

    public function toArray() {
        return [
            'IDPaciente' => $this->IDPaciente,
            'NombreCompleto' => $this->NombreCompleto,
            'Edad' => (int)$this->Edad,
            'Genero' => $this->Genero,
            'TipoSanguineo' => $this->TipoSanguineo,
            'Alergias' => $this->Alergias,
            'ContactoEmergencia' => $this->ContactoEmergencia
        ];
    }
}
?>
