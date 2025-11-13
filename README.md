# 🏥 Sistema de Gestión Hospitalaria

Sistema completo de gestión hospitalaria desarrollado en PHP con almacenamiento en archivos JSON.

## 📋 Descripción

Este sistema permite gestionar de manera integral:
- 👥 **Pacientes**: Registros completos con información médica
- 👨‍⚕️ **Doctores**: Información de especialización y disponibilidad
- 📅 **Citas**: Programación y seguimiento de consultas médicas
- 📊 **Reportes**: Consultas avanzadas y estadísticas

## ✨ Características

- ✅ CRUD completo para todas las entidades
- ✅ Validación de datos en cliente y servidor
- ✅ Búsqueda y filtrado avanzado
- ✅ Interfaz responsiva moderna
- ✅ Actualización de diagnósticos post-consulta
- ✅ Prevención de conflictos de horarios
- ✅ Reportes por rango de fechas
- ✅ Estadísticas en tiempo real

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Almacenamiento**: JSON
- **Frontend**: HTML5, CSS3, JavaScript
- **Arquitectura**: MVC (Modelo-Vista-Controlador)

## 📁 Estructura del Proyecto

```
proyectoclaude/
│
├── index.php                 # Página principal con menú
├── config/
│   └── config.php           # Configuración y funciones globales
│
├── models/                  # Modelos de datos
│   ├── Paciente.php
│   ├── Doctor.php
│   └── Cita.php
│
├── controllers/             # Controladores CRUD
│   ├── PacienteController.php
│   ├── DoctorController.php
│   ├── CitaController.php
│   └── estadisticas.php
│
├── views/                   # Vistas/Formularios
│   ├── pacientes.php
│   ├── doctores.php
│   ├── citas.php
│   ├── consultas.php
│   └── autor.php
│
├── data/                    # Archivos JSON (generados automáticamente)
│   ├── pacientes.json
│   ├── doctores.json
│   └── citas.json
│
├── css/
│   └── styles.css          # Estilos globales
│
└── js/
    └── main.js             # JavaScript principal
```

## 🚀 Instalación

### Requisitos Previos

- PHP 7.4 o superior
- Servidor web (Apache, Nginx, o PHP built-in server)
- Navegador web moderno

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd proyectoclaude
   ```

2. **Configurar permisos** (Linux/Mac)
   ```bash
   chmod -R 755 .
   chmod -R 777 data/
   ```

3. **Iniciar servidor PHP** (opción simple)
   ```bash
   php -S localhost:8000
   ```

4. **O configurar en Apache/Nginx**
   - Copiar proyecto a carpeta del servidor (ej: htdocs, www)
   - Acceder vía navegador: `http://localhost/proyectoclaude`

## 💻 Uso del Sistema

### Menú Principal

El sistema cuenta con un menú lateral con las siguientes opciones:

1. **Mantenimiento**
   - Gestión de Pacientes
   - Gestión de Doctores
   - Gestión de Citas

2. **Consultas y Reportes**
   - Estadísticas generales
   - Filtros avanzados
   - Reportes por fecha/doctor

3. **Autor**
   - Información del proyecto

4. **Salir**
   - Cerrar sesión

### Gestión de Pacientes

- **Crear**: Click en "Nuevo Paciente", llenar formulario
- **Editar**: Click en botón "Editar" de la fila deseada
- **Eliminar**: Click en "Eliminar" (verifica que no tenga citas activas)
- **Buscar**: Usar barra de búsqueda superior

**Campos requeridos:**
- Nombre Completo (mín. 3 caracteres)
- Edad (0-150)
- Género (Masculino/Femenino/Otro)
- Tipo Sanguíneo (A+, A-, B+, B-, AB+, AB-, O+, O-)
- Contacto de Emergencia (formato telefónico)

### Gestión de Doctores

- **Crear**: Click en "Nuevo Doctor", llenar formulario
- **Editar**: Click en botón "Editar"
- **Eliminar**: Click en "Eliminar" (verifica que no tenga citas activas)
- **Buscar**: Por nombre o especialización

**Campos requeridos:**
- Nombre (mín. 3 caracteres)
- Especialización
- Teléfono
- Email (formato válido)
- Turno (Mañana/Tarde/Noche)
- Pacientes Activos (número)

### Gestión de Citas

- **Crear**: Seleccionar paciente, doctor, fecha y hora
- **Editar**: Modificar detalles de la cita
- **Actualizar Diagnóstico**: Botón especial para citas programadas
- **Filtrar**: Por fecha, doctor o estado
- **Eliminar**: Cancelar cita

**Validaciones automáticas:**
- No permite citas en fechas pasadas
- Previene conflictos de horario del doctor
- Verifica existencia de paciente y doctor

### Consultas y Reportes

- **Estadísticas generales**: Vista automática al entrar
- **Filtros avanzados**: Por rango de fechas, doctor, estado
- **Resumen por especialización**: Cantidad de doctores y citas
- **Exportar resultados**: Ver en tabla detallada

## 🔒 Validaciones Implementadas

### Validaciones del Sistema

1. **Pacientes**
   - Nombre completo válido
   - Edad en rango válido (0-150)
   - Tipo sanguíneo correcto
   - Teléfono en formato válido
   - No eliminar si tiene citas activas

2. **Doctores**
   - Email único en el sistema
   - Teléfono válido
   - Especialización requerida
   - No eliminar si tiene citas activas

3. **Citas**
   - Paciente debe existir
   - Doctor debe existir
   - Fecha no puede ser pasada
   - No conflictos de horario
   - Formato de hora válido (HH:MM)

## 📊 Archivos de Datos

Los datos se almacenan en formato JSON en la carpeta `data/`:

- `pacientes.json`: Array de objetos Paciente
- `doctores.json`: Array de objetos Doctor
- `citas.json`: Array de objetos Cita

Los archivos se crean automáticamente al iniciar el sistema.

## 🐛 Solución de Problemas

### Error: "Permission denied" en data/

```bash
chmod -R 777 data/
```

### Error: "Cannot modify header information"

Asegúrate de que no haya salida antes de las etiquetas PHP.

### Los cambios no se reflejan

Limpia la caché del navegador (Ctrl+F5) o usa modo incógnito.

### Error 404 en controladores

Verifica que la ruta relativa en fetch() sea correcta según tu estructura.

## 👨‍💻 Autor

Sistema desarrollado como proyecto académico.

**Versión**: 1.0.0
**Fecha**: 2025

## 📝 Licencia

Este proyecto es de código abierto y está disponible para fines educativos.

## 🙏 Agradecimientos

Desarrollado con 💚 para el aprendizaje de programación web con PHP.

---

**Nota**: Este es un sistema educativo. Para uso en producción, se recomienda implementar:
- Base de datos SQL
- Sistema de autenticación
- Cifrado de datos sensibles
- Validación adicional de seguridad
- Logs de auditoría
