<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Doctores</title>
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            width: 300px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .data-grid {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .data-grid th {
            background: #2196F3;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        .data-grid td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }
        .data-grid tr:hover {
            background: #f5f5f5;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #2196F3;
            color: white;
        }
        .btn-primary:hover {
            background: #0b7dda;
        }
        .btn-edit {
            background: #FF9800;
            color: white;
            margin-right: 5px;
        }
        .btn-edit:hover {
            background: #e68900;
        }
        .btn-delete {
            background: #f44336;
            color: white;
        }
        .btn-delete:hover {
            background: #da190b;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 30px;
            border: 1px solid #888;
            width: 600px;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #2196F3;
        }
        .error-message {
            color: #f44336;
            font-size: 12px;
            margin-top: 5px;
        }
        .success-message {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
        .form-actions {
            margin-top: 20px;
            text-align: right;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-manana {
            background: #FFF9C4;
            color: #F57F17;
        }
        .badge-tarde {
            background: #FFCCBC;
            color: #BF360C;
        }
        .badge-noche {
            background: #C5CAE9;
            color: #283593;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h2>Gestión de Doctores</h2>
        <button class="btn btn-primary" onclick="abrirModal()">➕ Nuevo Doctor</button>
    </div>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍 Buscar doctor..." onkeyup="buscarDoctor()">
    </div>

    <div id="mensaje"></div>

    <table class="data-grid" id="tablaDoctores">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Especialización</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Turno</th>
                <th>Pacientes Activos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaDoctoresBody">
            <tr>
                <td colspan="8" class="empty-state">Cargando...</td>
            </tr>
        </tbody>
    </table>

    <!-- Modal para Crear/Editar Doctor -->
    <div id="modalDoctor" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2 id="modalTitle">Nuevo Doctor</h2>
            <form id="formDoctor" onsubmit="guardarDoctor(event)">
                <input type="hidden" id="IDDoctor" name="IDDoctor">

                <div class="form-group">
                    <label for="Nombre">Nombre Completo *</label>
                    <input type="text" id="Nombre" name="Nombre" required>
                    <span class="error-message" id="errorNombre"></span>
                </div>

                <div class="form-group">
                    <label for="Especializacion">Especialización *</label>
                    <select id="Especializacion" name="Especializacion" required>
                        <option value="">Seleccione...</option>
                        <option value="Cardiología">Cardiología</option>
                        <option value="Pediatría">Pediatría</option>
                        <option value="Neurología">Neurología</option>
                        <option value="Dermatología">Dermatología</option>
                        <option value="Oftalmología">Oftalmología</option>
                        <option value="Traumatología">Traumatología</option>
                        <option value="Ginecología">Ginecología</option>
                        <option value="Medicina General">Medicina General</option>
                        <option value="Psiquiatría">Psiquiatría</option>
                        <option value="Oncología">Oncología</option>
                    </select>
                    <span class="error-message" id="errorEspecializacion"></span>
                </div>

                <div class="form-group">
                    <label for="TelefonoContacto">Teléfono de Contacto *</label>
                    <input type="text" id="TelefonoContacto" name="TelefonoContacto"
                           placeholder="Ej: 809-555-1234" required>
                    <span class="error-message" id="errorTelefono"></span>
                </div>

                <div class="form-group">
                    <label for="Email">Email *</label>
                    <input type="email" id="Email" name="Email" required>
                    <span class="error-message" id="errorEmail"></span>
                </div>

                <div class="form-group">
                    <label for="Turno">Turno *</label>
                    <select id="Turno" name="Turno" required>
                        <option value="">Seleccione...</option>
                        <option value="Mañana">Mañana (8:00 AM - 2:00 PM)</option>
                        <option value="Tarde">Tarde (2:00 PM - 8:00 PM)</option>
                        <option value="Noche">Noche (8:00 PM - 2:00 AM)</option>
                    </select>
                    <span class="error-message" id="errorTurno"></span>
                </div>

                <div class="form-group">
                    <label for="PacientesActivos">Pacientes Activos</label>
                    <input type="number" id="PacientesActivos" name="PacientesActivos" min="0" value="0">
                    <span class="error-message" id="errorPacientes"></span>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let modoEdicion = false;

        // Cargar doctores al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            cargarDoctores();
        });

        function cargarDoctores() {
            fetch('../controllers/DoctorController.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarDoctores(data.data);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function mostrarDoctores(doctores) {
            const tbody = document.getElementById('tablaDoctoresBody');

            if (doctores.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="empty-state">No hay doctores registrados</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            doctores.forEach(doctor => {
                const turnoClass = doctor.Turno === 'Mañana' ? 'badge-manana' :
                                  doctor.Turno === 'Tarde' ? 'badge-tarde' : 'badge-noche';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${doctor.IDDoctor}</td>
                    <td>${doctor.Nombre}</td>
                    <td>${doctor.Especializacion}</td>
                    <td>${doctor.TelefonoContacto}</td>
                    <td>${doctor.Email}</td>
                    <td><span class="badge ${turnoClass}">${doctor.Turno}</span></td>
                    <td>${doctor.PacientesActivos}</td>
                    <td>
                        <button class="btn btn-edit" onclick="editarDoctor('${doctor.IDDoctor}')">✏️ Editar</button>
                        <button class="btn btn-delete" onclick="eliminarDoctor('${doctor.IDDoctor}')">🗑️ Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function abrirModal() {
            modoEdicion = false;
            document.getElementById('modalTitle').textContent = 'Nuevo Doctor';
            document.getElementById('formDoctor').reset();
            document.getElementById('IDDoctor').value = '';
            limpiarErrores();
            document.getElementById('modalDoctor').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalDoctor').style.display = 'none';
            limpiarErrores();
        }

        function guardarDoctor(event) {
            event.preventDefault();
            limpiarErrores();

            const formData = new FormData(document.getElementById('formDoctor'));
            const action = modoEdicion ? 'actualizar' : 'crear';
            formData.append('action', action);

            fetch('../controllers/DoctorController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    cerrarModal();
                    cargarDoctores();
                } else {
                    if (data.errors) {
                        data.errors.forEach(error => {
                            mostrarMensaje(error, 'error');
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error al guardar el doctor', 'error');
            });
        }

        function editarDoctor(id) {
            modoEdicion = true;
            document.getElementById('modalTitle').textContent = 'Editar Doctor';

            fetch(`../controllers/DoctorController.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const doctor = data.data;
                        document.getElementById('IDDoctor').value = doctor.IDDoctor;
                        document.getElementById('Nombre').value = doctor.Nombre;
                        document.getElementById('Especializacion').value = doctor.Especializacion;
                        document.getElementById('TelefonoContacto').value = doctor.TelefonoContacto;
                        document.getElementById('Email').value = doctor.Email;
                        document.getElementById('Turno').value = doctor.Turno;
                        document.getElementById('PacientesActivos').value = doctor.PacientesActivos;
                        document.getElementById('modalDoctor').style.display = 'block';
                    }
                });
        }

        function eliminarDoctor(id) {
            if (confirm('¿Está seguro de eliminar este doctor?')) {
                const formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('IDDoctor', id);

                fetch('../controllers/DoctorController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensaje(data.message, 'success');
                        cargarDoctores();
                    } else {
                        mostrarMensaje(data.errors[0], 'error');
                    }
                });
            }
        }

        function buscarDoctor() {
            const termino = document.getElementById('searchInput').value;

            if (termino.length === 0) {
                cargarDoctores();
                return;
            }

            fetch(`../controllers/DoctorController.php?action=buscar&termino=${termino}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarDoctores(data.data);
                    }
                });
        }

        function mostrarMensaje(mensaje, tipo) {
            const div = document.getElementById('mensaje');
            div.innerHTML = `<div class="${tipo === 'success' ? 'success-message' : 'error-message'}">${mensaje}</div>`;
            setTimeout(() => {
                div.innerHTML = '';
            }, 3000);
        }

        function limpiarErrores() {
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalDoctor');
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>
</body>
</html>
