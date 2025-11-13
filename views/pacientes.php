<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes</title>
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
            background: #4CAF50;
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
            background: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background: #45a049;
        }
        .btn-edit {
            background: #2196F3;
            color: white;
            margin-right: 5px;
        }
        .btn-edit:hover {
            background: #0b7dda;
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
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #4CAF50;
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
    </style>
</head>
<body>
    <div class="page-header">
        <h2>Gestión de Pacientes</h2>
        <button class="btn btn-primary" onclick="abrirModal()">➕ Nuevo Paciente</button>
    </div>

    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="🔍 Buscar paciente..." onkeyup="buscarPaciente()">
    </div>

    <div id="mensaje"></div>

    <table class="data-grid" id="tablaPacientes">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Edad</th>
                <th>Género</th>
                <th>Tipo Sanguíneo</th>
                <th>Alergias</th>
                <th>Contacto Emergencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaPacientesBody">
            <tr>
                <td colspan="8" class="empty-state">Cargando...</td>
            </tr>
        </tbody>
    </table>

    <!-- Modal para Crear/Editar Paciente -->
    <div id="modalPaciente" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2 id="modalTitle">Nuevo Paciente</h2>
            <form id="formPaciente" onsubmit="guardarPaciente(event)">
                <input type="hidden" id="IDPaciente" name="IDPaciente">

                <div class="form-group">
                    <label for="NombreCompleto">Nombre Completo *</label>
                    <input type="text" id="NombreCompleto" name="NombreCompleto" required>
                    <span class="error-message" id="errorNombre"></span>
                </div>

                <div class="form-group">
                    <label for="Edad">Edad *</label>
                    <input type="number" id="Edad" name="Edad" min="0" max="150" required>
                    <span class="error-message" id="errorEdad"></span>
                </div>

                <div class="form-group">
                    <label for="Genero">Género *</label>
                    <select id="Genero" name="Genero" required>
                        <option value="">Seleccione...</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                    <span class="error-message" id="errorGenero"></span>
                </div>

                <div class="form-group">
                    <label for="TipoSanguineo">Tipo Sanguíneo *</label>
                    <select id="TipoSanguineo" name="TipoSanguineo" required>
                        <option value="">Seleccione...</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <span class="error-message" id="errorTipoSanguineo"></span>
                </div>

                <div class="form-group">
                    <label for="Alergias">Alergias</label>
                    <textarea id="Alergias" name="Alergias" rows="3" placeholder="Ingrese las alergias o 'Ninguna'">Ninguna</textarea>
                    <span class="error-message" id="errorAlergias"></span>
                </div>

                <div class="form-group">
                    <label for="ContactoEmergencia">Contacto de Emergencia *</label>
                    <input type="text" id="ContactoEmergencia" name="ContactoEmergencia"
                           placeholder="Ej: 809-555-1234" required>
                    <span class="error-message" id="errorContacto"></span>
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

        // Cargar pacientes al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            cargarPacientes();
        });

        function cargarPacientes() {
            fetch('../controllers/PacienteController.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarPacientes(data.data);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function mostrarPacientes(pacientes) {
            const tbody = document.getElementById('tablaPacientesBody');

            if (pacientes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="empty-state">No hay pacientes registrados</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            pacientes.forEach(paciente => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${paciente.IDPaciente}</td>
                    <td>${paciente.NombreCompleto}</td>
                    <td>${paciente.Edad}</td>
                    <td>${paciente.Genero}</td>
                    <td>${paciente.TipoSanguineo}</td>
                    <td>${paciente.Alergias}</td>
                    <td>${paciente.ContactoEmergencia}</td>
                    <td>
                        <button class="btn btn-edit" onclick="editarPaciente('${paciente.IDPaciente}')">✏️ Editar</button>
                        <button class="btn btn-delete" onclick="eliminarPaciente('${paciente.IDPaciente}')">🗑️ Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function abrirModal() {
            modoEdicion = false;
            document.getElementById('modalTitle').textContent = 'Nuevo Paciente';
            document.getElementById('formPaciente').reset();
            document.getElementById('IDPaciente').value = '';
            limpiarErrores();
            document.getElementById('modalPaciente').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalPaciente').style.display = 'none';
            limpiarErrores();
        }

        function guardarPaciente(event) {
            event.preventDefault();
            limpiarErrores();

            const formData = new FormData(document.getElementById('formPaciente'));
            const action = modoEdicion ? 'actualizar' : 'crear';
            formData.append('action', action);

            fetch('../controllers/PacienteController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    cerrarModal();
                    cargarPacientes();
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
                mostrarMensaje('Error al guardar el paciente', 'error');
            });
        }

        function editarPaciente(id) {
            modoEdicion = true;
            document.getElementById('modalTitle').textContent = 'Editar Paciente';

            fetch(`../controllers/PacienteController.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const paciente = data.data;
                        document.getElementById('IDPaciente').value = paciente.IDPaciente;
                        document.getElementById('NombreCompleto').value = paciente.NombreCompleto;
                        document.getElementById('Edad').value = paciente.Edad;
                        document.getElementById('Genero').value = paciente.Genero;
                        document.getElementById('TipoSanguineo').value = paciente.TipoSanguineo;
                        document.getElementById('Alergias').value = paciente.Alergias;
                        document.getElementById('ContactoEmergencia').value = paciente.ContactoEmergencia;
                        document.getElementById('modalPaciente').style.display = 'block';
                    }
                });
        }

        function eliminarPaciente(id) {
            if (confirm('¿Está seguro de eliminar este paciente?')) {
                const formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('IDPaciente', id);

                fetch('../controllers/PacienteController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensaje(data.message, 'success');
                        cargarPacientes();
                    } else {
                        mostrarMensaje(data.errors[0], 'error');
                    }
                });
            }
        }

        function buscarPaciente() {
            const termino = document.getElementById('searchInput').value;

            if (termino.length === 0) {
                cargarPacientes();
                return;
            }

            fetch(`../controllers/PacienteController.php?action=buscar&termino=${termino}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarPacientes(data.data);
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
            const modal = document.getElementById('modalPaciente');
            if (event.target == modal) {
                cerrarModal();
            }
        }
    </script>
</body>
</html>
