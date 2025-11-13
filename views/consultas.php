<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas y Reportes</title>
    <style>
        .page-header {
            margin-bottom: 30px;
        }
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .report-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .report-card h3 {
            margin-top: 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .report-card .stat-number {
            font-size: 48px;
            font-weight: bold;
            color: #4CAF50;
            margin: 15px 0;
        }
        .report-card p {
            color: #666;
            margin: 5px 0;
        }
        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 10px;
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
        .btn {
            padding: 10px 20px;
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
        .results-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .data-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        .chart-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .info-list {
            list-style: none;
            padding: 0;
        }
        .info-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .info-list li:last-child {
            border-bottom: none;
        }
        .info-list strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h2>📊 Consultas y Reportes</h2>
        <p>Visualice estadísticas y genere reportes del sistema</p>
    </div>

    <!-- Estadísticas Generales -->
    <div class="reports-grid">
        <div class="report-card">
            <h3>👥 Total Pacientes</h3>
            <div class="stat-number" id="totalPacientes">0</div>
            <p>Pacientes registrados en el sistema</p>
        </div>

        <div class="report-card">
            <h3>👨‍⚕️ Total Doctores</h3>
            <div class="stat-number" id="totalDoctores">0</div>
            <p>Doctores activos</p>
        </div>

        <div class="report-card">
            <h3>📅 Citas Programadas</h3>
            <div class="stat-number" id="citasProgramadas">0</div>
            <p>Citas pendientes</p>
        </div>

        <div class="report-card">
            <h3>✅ Citas Completadas</h3>
            <div class="stat-number" id="citasCompletadas">0</div>
            <p>Consultas realizadas</p>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="filters-section">
        <h3>🔍 Filtros de Búsqueda Avanzada</h3>
        <div class="filter-row">
            <div class="form-group">
                <label>Rango de Fechas - Inicio</label>
                <input type="date" id="fechaInicio">
            </div>
            <div class="form-group">
                <label>Rango de Fechas - Fin</label>
                <input type="date" id="fechaFin">
            </div>
            <div class="form-group">
                <label>Doctor</label>
                <select id="filtroDoctorReporte">
                    <option value="">Todos los doctores</option>
                </select>
            </div>
            <div class="form-group">
                <label>Estado de Cita</label>
                <select id="filtroEstadoReporte">
                    <option value="">Todos</option>
                    <option value="Programada">Programada</option>
                    <option value="Completada">Completada</option>
                    <option value="Cancelada">Cancelada</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary" onclick="generarReporte()">📊 Generar Reporte</button>
    </div>

    <!-- Resultados -->
    <div class="results-section">
        <h3>Resultados del Reporte</h3>
        <div id="resumenReporte"></div>
        <table class="data-grid" id="tablaReporte">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Doctor</th>
                    <th>Especialización</th>
                    <th>Estado</th>
                    <th>Diagnóstico</th>
                </tr>
            </thead>
            <tbody id="tablaReporteBody">
                <tr>
                    <td colspan="7" class="empty-state">Use los filtros para generar un reporte</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Información Adicional -->
    <div class="chart-section">
        <h3>📈 Resumen por Especialización</h3>
        <div id="resumenEspecializacion"></div>
    </div>

    <script>
        let doctores = [];
        let pacientes = [];

        document.addEventListener('DOMContentLoaded', function() {
            cargarEstadisticas();
            cargarDoctores();
            cargarPacientes();
        });

        function cargarEstadisticas() {
            Promise.all([
                fetch('../controllers/PacienteController.php?action=listar'),
                fetch('../controllers/DoctorController.php?action=listar'),
                fetch('../controllers/CitaController.php?action=listar')
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([resPacientes, resDoctores, resCitas]) => {
                const totalPacientes = resPacientes.data.length;
                const totalDoctores = resDoctores.data.length;

                const citasProgramadas = resCitas.data.filter(c => c.Estado === 'Programada').length;
                const citasCompletadas = resCitas.data.filter(c => c.Estado === 'Completada').length;

                document.getElementById('totalPacientes').textContent = totalPacientes;
                document.getElementById('totalDoctores').textContent = totalDoctores;
                document.getElementById('citasProgramadas').textContent = citasProgramadas;
                document.getElementById('citasCompletadas').textContent = citasCompletadas;

                // Resumen por especialización
                mostrarResumenEspecializacion(resDoctores.data, resCitas.data);
            });
        }

        function cargarDoctores() {
            fetch('../controllers/DoctorController.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        doctores = data.data;
                        const select = document.getElementById('filtroDoctorReporte');
                        select.innerHTML = '<option value="">Todos los doctores</option>';

                        doctores.forEach(doc => {
                            const option = document.createElement('option');
                            option.value = doc.IDDoctor;
                            option.textContent = `Dr. ${doc.Nombre} - ${doc.Especializacion}`;
                            select.appendChild(option);
                        });
                    }
                });
        }

        function cargarPacientes() {
            fetch('../controllers/PacienteController.php?action=listar')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        pacientes = data.data;
                    }
                });
        }

        function mostrarResumenEspecializacion(doctoresData, citasData) {
            const especializaciones = {};

            doctoresData.forEach(doc => {
                const esp = doc.Especializacion;
                if (!especializaciones[esp]) {
                    especializaciones[esp] = {
                        doctores: 0,
                        citas: 0
                    };
                }
                especializaciones[esp].doctores++;

                // Contar citas del doctor
                const citasDoctor = citasData.filter(c => c.IDDoctor === doc.IDDoctor);
                especializaciones[esp].citas += citasDoctor.length;
            });

            const container = document.getElementById('resumenEspecializacion');
            let html = '<ul class="info-list">';

            for (const [esp, datos] of Object.entries(especializaciones)) {
                html += `<li>
                    <strong>${esp}:</strong>
                    ${datos.doctores} doctor(es) - ${datos.citas} cita(s) total
                </li>`;
            }

            html += '</ul>';
            container.innerHTML = html;
        }

        function generarReporte() {
            const fechaInicio = document.getElementById('fechaInicio').value;
            const fechaFin = document.getElementById('fechaFin').value;
            const idDoctor = document.getElementById('filtroDoctorReporte').value;
            const estado = document.getElementById('filtroEstadoReporte').value;

            let url = '../controllers/CitaController.php?action=filtrar';
            if (fechaInicio) url += `&fechaInicio=${fechaInicio}`;
            if (fechaFin) url += `&fechaFin=${fechaFin}`;
            if (idDoctor) url += `&idDoctor=${idDoctor}`;
            if (estado) url += `&estado=${estado}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarReporte(data.data);
                    }
                });
        }

        function mostrarReporte(citas) {
            const tbody = document.getElementById('tablaReporteBody');
            const resumen = document.getElementById('resumenReporte');

            if (citas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No se encontraron resultados</td></tr>';
                resumen.innerHTML = '<p>No hay datos para mostrar</p>';
                return;
            }

            // Resumen
            resumen.innerHTML = `
                <p style="background: #E3F2FD; padding: 15px; border-radius: 4px; margin: 15px 0;">
                    <strong>Total de citas encontradas: ${citas.length}</strong><br>
                    Programadas: ${citas.filter(c => c.Estado === 'Programada').length} |
                    Completadas: ${citas.filter(c => c.Estado === 'Completada').length} |
                    Canceladas: ${citas.filter(c => c.Estado === 'Cancelada').length}
                </p>
            `;

            // Tabla
            tbody.innerHTML = '';
            citas.forEach(cita => {
                const doctor = doctores.find(d => d.IDDoctor === cita.IDDoctor);
                const estadoClass = cita.Estado === 'Programada' ? 'badge-programada' :
                                   cita.Estado === 'Completada' ? 'badge-completada' : 'badge-cancelada';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${cita.Fecha}</td>
                    <td>${cita.Hora}</td>
                    <td>${cita.NombrePaciente || 'N/A'}</td>
                    <td>${cita.NombreDoctor || 'N/A'}</td>
                    <td>${doctor ? doctor.Especializacion : 'N/A'}</td>
                    <td><span class="badge ${estadoClass}">${cita.Estado}</span></td>
                    <td>${cita.NotasDiagnostico || 'Sin diagnóstico'}</td>
                `;
                tbody.appendChild(tr);
            });
        }
    </script>
</body>
</html>
