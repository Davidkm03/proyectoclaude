<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Autor</title>
    <style>
        .author-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .author-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .author-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
        }
        .author-card h1 {
            margin: 20px 0 10px;
            color: #333;
        }
        .author-card .subtitle {
            color: #666;
            font-size: 18px;
            margin-bottom: 30px;
        }
        .info-section {
            background: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .info-section h2 {
            color: #4CAF50;
            margin-top: 0;
        }
        .info-list {
            list-style: none;
            padding: 0;
        }
        .info-list li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .info-list li:last-child {
            border-bottom: none;
        }
        .info-list strong {
            color: #333;
            display: inline-block;
            width: 150px;
        }
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .tech-badge {
            background: #4CAF50;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .footer-note {
            margin-top: 30px;
            padding: 20px;
            background: #E3F2FD;
            border-radius: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="author-container">
        <div class="author-card">
            <div class="author-avatar">
                👨‍💻
            </div>
            <h1>Sistema de Gestión Hospitalaria</h1>
            <p class="subtitle">Desarrollado en PHP - Versión 1.0.0</p>

            <div class="info-section">
                <h2>📝 Información del Proyecto</h2>
                <ul class="info-list">
                    <li><strong>Autor:</strong> [Tu Nombre Completo]</li>
                    <li><strong>Matrícula:</strong> [Tu Matrícula]</li>
                    <li><strong>Institución:</strong> [Tu Universidad/Instituto]</li>
                    <li><strong>Asignatura:</strong> Programación [Nombre de la Asignatura]</li>
                    <li><strong>Profesor:</strong> [Nombre del Profesor]</li>
                    <li><strong>Fecha:</strong> <?php echo date('d/m/Y'); ?></li>
                </ul>
            </div>

            <div class="info-section">
                <h2>🎯 Objetivos del Sistema</h2>
                <p style="text-align: left; line-height: 1.8;">
                    Este sistema fue desarrollado como proyecto académico con el objetivo de implementar
                    un sistema completo de gestión hospitalaria que permita:
                </p>
                <ul style="text-align: left; line-height: 1.8;">
                    <li>Gestionar registros de pacientes con información médica completa</li>
                    <li>Administrar información de doctores y sus especializaciones</li>
                    <li>Programar y gestionar citas médicas</li>
                    <li>Actualizar diagnósticos post-consulta</li>
                    <li>Generar reportes y consultas avanzadas</li>
                    <li>Filtrar información por múltiples criterios</li>
                </ul>
            </div>

            <div class="info-section">
                <h2>💻 Tecnologías Utilizadas</h2>
                <div class="tech-stack">
                    <span class="tech-badge">PHP 7.4+</span>
                    <span class="tech-badge">JSON</span>
                    <span class="tech-badge">HTML5</span>
                    <span class="tech-badge">CSS3</span>
                    <span class="tech-badge">JavaScript</span>
                    <span class="tech-badge">AJAX</span>
                    <span class="tech-badge">Arquitectura MVC</span>
                </div>
            </div>

            <div class="info-section">
                <h2>🏗️ Arquitectura del Sistema</h2>
                <p style="text-align: left; line-height: 1.8;">
                    El sistema sigue el patrón de diseño <strong>Modelo-Vista-Controlador (MVC)</strong>:
                </p>
                <ul style="text-align: left; line-height: 1.8;">
                    <li><strong>Modelos:</strong> Clases PHP que representan las entidades (Paciente, Doctor, Cita)</li>
                    <li><strong>Vistas:</strong> Interfaces HTML con JavaScript para interacción del usuario</li>
                    <li><strong>Controladores:</strong> Lógica de negocio y operaciones CRUD</li>
                    <li><strong>Almacenamiento:</strong> Archivos JSON para persistencia de datos</li>
                </ul>
            </div>

            <div class="info-section">
                <h2>✨ Características Principales</h2>
                <ul style="text-align: left; line-height: 1.8;">
                    <li>✅ CRUD completo para Pacientes, Doctores y Citas</li>
                    <li>✅ Validación de datos en cliente y servidor</li>
                    <li>✅ Búsqueda y filtrado avanzado</li>
                    <li>✅ Interfaz responsiva y moderna</li>
                    <li>✅ Manejo de relaciones entre entidades</li>
                    <li>✅ Actualización de diagnósticos post-consulta</li>
                    <li>✅ Reportes y estadísticas en tiempo real</li>
                    <li>✅ Prevención de conflictos de horarios</li>
                    <li>✅ Validación de eliminación con referencias</li>
                </ul>
            </div>

            <div class="footer-note">
                <p style="margin: 0; color: #333;">
                    <strong>Sistema desarrollado con 💚 para fines educativos</strong><br>
                    Todos los derechos reservados © <?php echo date('Y'); ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
