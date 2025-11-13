# 🚀 Inicio Rápido - Sistema de Gestión Hospitalaria

## ⚡ Ejecutar en 3 pasos

### 1️⃣ Abrir terminal en la carpeta del proyecto
```bash
cd /home/user/proyectoclaude
```

### 2️⃣ Iniciar servidor PHP
```bash
php -S localhost:8000
```

### 3️⃣ Abrir navegador
```
http://localhost:8000
```

## 🎯 Primeros Pasos

### 1. Registrar un Doctor
- Click en **Mantenimiento** → **Gestión de Doctores**
- Click en **➕ Nuevo Doctor**
- Completar formulario y guardar

### 2. Registrar un Paciente
- Click en **Mantenimiento** → **Gestión de Pacientes**
- Click en **➕ Nuevo Paciente**
- Completar formulario y guardar

### 3. Crear una Cita
- Click en **Mantenimiento** → **Gestión de Citas**
- Click en **➕ Nueva Cita**
- Seleccionar paciente y doctor
- Elegir fecha y hora
- Guardar

### 4. Ver Reportes
- Click en **Consultas y Reportes**
- Usar filtros para generar reportes
- Ver estadísticas en tiempo real

## 📱 Datos de Prueba (Opcional)

### Doctor de ejemplo:
- **Nombre**: Dr. Juan Pérez
- **Especialización**: Cardiología
- **Teléfono**: 809-555-1234
- **Email**: juan.perez@hospital.com
- **Turno**: Mañana

### Paciente de ejemplo:
- **Nombre**: María García
- **Edad**: 35
- **Género**: Femenino
- **Tipo Sanguíneo**: O+
- **Alergias**: Ninguna
- **Contacto**: 809-555-5678

### Cita de ejemplo:
- Seleccionar paciente y doctor creados
- **Fecha**: Fecha futura
- **Hora**: 09:00
- **Estado**: Programada

## 🔥 Atajos de Teclado

- **ESC**: Cerrar modal actual
- **Ctrl+F5**: Recargar sin caché

## 📂 Ubicación de Datos

Los datos se guardan automáticamente en:
```
data/pacientes.json
data/doctores.json
data/citas.json
```

## ⚠️ Nota Importante

Si tienes problemas con permisos en Linux/Mac:
```bash
chmod -R 777 data/
```

## 🆘 Soporte

Si encuentras algún problema:
1. Verifica que PHP esté instalado: `php -v`
2. Asegúrate de estar en la carpeta correcta
3. Revisa la consola del navegador (F12)
4. Consulta el README.md para más detalles

---

**¡Listo para usar! 🎉**
