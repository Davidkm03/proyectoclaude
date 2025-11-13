<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas</title>
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .filters-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .filter-group {
            flex: 1;
        }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 12px;
            color: #555;
        }
        .filter-group input, .filter-group select {
            width: 100%;
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
            background: #9C27B0;
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
            background: #9C27B0;
            color: white;
        }
        .btn-primary:hover {
            background: #7B1FA2;
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
        .btn-diagnostico {
            background: #4CAF50;
            color: white;
            margin-right: 5px;
        }
        .btn-diagnostico:hover {
            background: #45a049;
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
            width: 700px;
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
            border-color: #9C27B0;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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
        .badge-programada {
            background: #E3F2FD;
            color: #1976D2;
        }
        .badge-completada {
            background: #C8E6C9;
            color: #388E3C;
        }
        .badge-cancelada {
            background: #FFCDD2;
            color: #D32F2F;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h2>Gestión de Citas Médicas</h2>
        <button class="btn btn-primary" onclick="abrirModal()">➕ Nueva Cita</button>
    </div>

    <div class="filters-bar">
        <div class="filter-group">
            <label>Fecha Inicio</label>
            <input type="date" id="filtroFechaInicio">
        </div>
        <div class="filter-group">
            <label>Fecha Fin</label>
            <input type="date" id="filtroFechaFin">
        </div>
        <div class="filter-group">
            <label>Doctor</label>
            <select id="filtroDoctor">
                <option value="">Todos los doctores</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Estado</label>
            <select id="filtroEstado">
                <option value="">Todos los estados</option>
                <option value="Programada">Programada</option>
                <option value="Completada">Completada</option>
                <option value="Cancelada">Cancelada</option>
            </select>
        </div>
        <div class="filter-group">
            <label>&nbsp;</label>
            <button class="btn btn-primary" onclick="aplicarFiltros()">🔍 Filtrar</button>
        </div>
    </div>

    <div id="mensaje"></div>

    <table class="data-grid" id="tablaCitas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Diagnóstico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaCitasBody">
            <tr>
                <td colspan="8" class="empty-state">Cargando...</td>
            </tr>
        </tbody>
    </table>

    <!-- Modal para Crear/Editar Cita -->
    <div id="modalCita" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2 id="modalTitle">Nueva Cita</h2>
            <form id="formCita" onsubmit="guardarCita(event)">
                <input type="hidden" id="IDCita" name="IDCita">

                <div class="form-group">
                    <label for="IDPaciente">Paciente *</label>
                    <select id="IDPaciente" name="IDPaciente" required>
                        <option value="">Seleccione un paciente...</option>
                    </select>
                    <span class="error-message" id="errorPaciente"></span>
                </div>

                <div class="form-group">
                    <label for="IDDoctor">Doctor *</label>
                    <select id="IDDoctor" name="IDDoctor" required>
                        <option value="">Seleccione un doctor...</option>
                    </select>
                    <span class="error-message" id="errorDoctor"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="Fecha">Fecha *</label>
                        <input type="date" id="Fecha" name="Fecha" required>
                        <span class="error-message" id="errorFecha"></span>
                    </div>

                    <div class="form-group">
                        <label for="Hora">Hora *</label>
                        <input type="time" id="Hora" name="Hora" required>
                        <span class="error-message" id="errorHora"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Estado">Estado *</label>
                    <select id="Estado" name="Estado" required>
                        <option value="Programada">Programada</option>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                    <span class="error-message" id="errorEstado"></span>
                </div>

                <div class="form-group">
                    <label for="NotasDiagnostico">Notas y Diagnóstico</label>
                    <textarea id="NotasDiagnostico" name="NotasDiagnostico" rows="4"
                              placeholder="Ingrese notas o diagnóstico de la consulta..."></textarea>
                    <span class="error-message" id="errorNotas"></span>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Actualizar Diagnóstico -->
    <div id="modalDiagnostico" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalDiagnostico()">&times;</span>
            <h2>Actualizar Diagnóstico</h2>
            <form id="formDiagnostico" onsubmit="guardarDiagnostico(event)">
                <input type="hidden" id="IDCitaDiagnostico" name="IDCita">

                <div class="form-group">
                    <label>Paciente:</label>
                    <p id="infoPaciente" style="font-weight: bold;"></p>
                </div>

                <div class="form-group">
                    <label>Doctor:</label>
                    <p id="infoDoctor" style="font-weight: bold;"></p>
                </div>

                <div class="form-group">
                    <label>Fecha y Hora:</label>
                    <p id="infoFechaHora" style="font-weight: bold;"></p>
                </div>

                <div class="form-group">
                    <label for="NotasDiagnostico2">Notas y Diagnóstico *</label>
                    <textarea id="NotasDiagnostico2" name="NotasDiagnostico" rows="6" required
                              placeholder="Ingrese el diagnóstico y notas de la consulta..."></textarea>
                </div>

                <div class="form-group">
                    <label for="EstadoDiagnostico">Estado de la Cita *</label>
                    <select id="EstadoDiagnostico" name="Estado" required>
                        <option value="Completada">Completada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn" onclick="cerrarModalDiagnostico()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Diagnóstico</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let modoEdicion = false;
        let pacientes = [];
        let doctores = [];

        // Cargar datos al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            cargarCitas();
            cargarPacientes();
            cargarDoctores();
            establecerFechaMinima();
        });

        function establecerFechaMinima() {
            const hoy = new Date().toISOString().split('T')[0];
            document.getElementById('Fecha').setAttribute('min', hoy);
        }

        function cargarPacientes() {
            fetch('../controllers/PacienteController.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        pacientes = data.data;
                        const select = document.getElementById('IDPaciente');
                        select.innerHTML = '<option value="">Seleccione un paciente...</option>';
                        pacientes.forEach(pac => {
                            const option = document.createElement('option');
                            option.value = pac.IDPaciente;
                            option.textContent = `${pac.NombreCompleto} (${pac.TipoSanguineo})`;
                            select.appendChild(option);
                        });
                    }
                });
        }

        function cargarDoctores() {
            fetch('../controllers/DoctorController.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        doctores = data.data;
                        const select = document.getElementById('IDDoctor');
                        const selectFiltro = document.getElementById('filtroDoctor');

                        select.innerHTML = '<option value="">Seleccione un doctor...</option>';
                        selectFiltro.innerHTML = '<option value="">Todos los doctores</option>';

                        doctores.forEach(doc => {
                            const option = document.createElement('option');
                            option.value = doc.IDDoctor;
                            option.textContent = `Dr. ${doc.Nombre} - ${doc.Especializacion}`;
                            select.appendChild(option);

                            const optionFiltro = option.cloneNode(true);
                            selectFiltro.appendChild(optionFiltro);
                        });
                    }
                });
        }

        function cargarCitas() {
            fetch('../controllers/CitaController.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarCitas(data.data);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function mostrarCitas(citas) {
            const tbody = document.getElementById('tablaCitasBody');

            if (citas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="empty-state">No hay citas registradas</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            citas.forEach(cita => {
                const estadoClass = cita.Estado === 'Programada' ? 'badge-programada' :
                                   cita.Estado === 'Completada' ? 'badge-completada' : 'badge-cancelada';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${cita.IDCita}</td>
                    <td>${cita.NombrePaciente || 'N/A'}</td>
                    <td>${cita.NombreDoctor || 'N/A'}</td>
                    <td>${cita.Fecha}</td>
                    <td>${cita.Hora}</td>
                    <td><span class="badge ${estadoClass}">${cita.Estado}</span></td>
                    <td>${cita.NotasDiagnostico || 'Sin diagnóstico'}</td>
                    <td>
                        ${cita.Estado === 'Programada' ? `<button class="btn btn-diagnostico" onclick="abrirModalDiagnostico('${cita.IDCita}')">📋 Diagnóstico</button>` : ''}
                        <button class="btn btn-edit" onclick="editarCita('${cita.IDCita}')">✏️ Editar</button>
                        <button class="btn btn-delete" onclick="eliminarCita('${cita.IDCita}')">🗑️ Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function aplicarFiltros() {
            const fechaInicio = document.getElementById('filtroFechaInicio').value;
            const fechaFin = document.getElementById('filtroFechaFin').value;
            const idDoctor = document.getElementById('filtroDoctor').value;
            const estado = document.getElementById('filtroEstado').value;

            let url = '../controllers/CitaController.php?action=filtrar';
            if (fechaInicio) url += `&fechaInicio=${fechaInicio}`;
            if (fechaFin) url += `&fechaFin=${fechaFin}`;
            if (idDoctor) url += `&idDoctor=${idDoctor}`;
            if (estado) url += `&estado=${estado}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarCitas(data.data);
                    }
                });
        }

        function abrirModal() {
            modoEdicion = false;
            document.getElementById('modalTitle').textContent = 'Nueva Cita';
            document.getElementById('formCita').reset();
            document.getElementById('IDCita').value = '';
            document.getElementById('Estado').value = 'Programada';
            limpiarErrores();
            document.getElementById('modalCita').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalCita').style.display = 'none';
            limpiarErrores();
        }

        function abrirModalDiagnostico(idCita) {
            fetch(`../controllers/CitaController.php?action=obtener&id=${idCita}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cita = data.data;
                        document.getElementById('IDCitaDiagnostico').value = cita.IDCita;

                        // Buscar nombres
                        const paciente = pacientes.find(p => p.IDPaciente === cita.IDPaciente);
                        const doctor = doctores.find(d => d.IDDoctor === cita.IDDoctor);

                        document.getElementById('infoPaciente').textContent = paciente ? paciente.NombreCompleto : 'N/A';
                        document.getElementById('infoDoctor').textContent = doctor ? `Dr. ${doctor.Nombre}` : 'N/A';
                        document.getElementById('infoFechaHora').textContent = `${cita.Fecha} a las ${cita.Hora}`;
                        document.getElementById('NotasDiagnostico2').value = cita.NotasDiagnostico || '';
                        document.getElementById('modalDiagnostico').style.display = 'block';
                    }
                });
        }

        function cerrarModalDiagnostico() {
            document.getElementById('modalDiagnostico').style.display = 'none';
        }

        function guardarDiagnostico(event) {
            event.preventDefault();

            const formData = new FormData(document.getElementById('formDiagnostico'));
            formData.append('action', 'actualizarDiagnostico');

            fetch('../controllers/CitaController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    cerrarModalDiagnostico();
                    cargarCitas();
                } else {
                    mostrarMensaje(data.errors[0], 'error');
                }
            });
        }

        function guardarCita(event) {
            event.preventDefault();
            limpiarErrores();

            const formData = new FormData(document.getElementById('formCita'));
            const action = modoEdicion ? 'actualizar' : 'crear';
            formData.append('action', action);

            fetch('../controllers/CitaController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje(data.message, 'success');
                    cerrarModal();
                    cargarCitas();
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
                mostrarMensaje('Error al guardar la cita', 'error');
            });
        }

        function editarCita(id) {
            modoEdicion = true;
            document.getElementById('modalTitle').textContent = 'Editar Cita';

            fetch(`../controllers/CitaController.php?action=obtener&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const cita = data.data;
                        document.getElementById('IDCita').value = cita.IDCita;
                        document.getElementById('IDPaciente').value = cita.IDPaciente;
                        document.getElementById('IDDoctor').value = cita.IDDoctor;
                        document.getElementById('Fecha').value = cita.Fecha;
                        document.getElementById('Hora').value = cita.Hora;
                        document.getElementById('Estado').value = cita.Estado;
                        document.getElementById('NotasDiagnostico').value = cita.NotasDiagnostico;
                        document.getElementById('modalCita').style.display = 'block';
                    }
                });
        }

        function eliminarCita(id) {
            if (confirm('¿Está seguro de eliminar esta cita?')) {
                const formData = new FormData();
                formData.append('action', 'eliminar');
                formData.append('IDCita', id);

                fetch('../controllers/CitaController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarMensaje(data.message, 'success');
                        cargarCitas();
                    } else {
                        mostrarMensaje(data.errors[0], 'error');
                    }
                });
            }
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

        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const modalCita = document.getElementById('modalCita');
            const modalDiagnostico = document.getElementById('modalDiagnostico');

            if (event.target == modalCita) {
                cerrarModal();
            }
            if (event.target == modalDiagnostico) {
                cerrarModalDiagnostico();
            }
        }
    </script>
</body>
</html>
