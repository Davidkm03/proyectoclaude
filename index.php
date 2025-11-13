<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Hospitalaria</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Menú Principal -->
        <nav class="main-menu">
            <div class="logo">
                <h1>🏥 Sistema Hospitalario</h1>
            </div>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="#" onclick="toggleSubmenu('mantenimiento')">
                        📋 Mantenimiento
                    </a>
                    <ul id="mantenimiento" class="submenu">
                        <li><a href="#" onclick="loadPage('views/pacientes.php')">Gestión de Pacientes</a></li>
                        <li><a href="#" onclick="loadPage('views/doctores.php')">Gestión de Doctores</a></li>
                        <li><a href="#" onclick="loadPage('views/citas.php')">Gestión de Citas</a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="loadPage('views/consultas.php')">
                        🔍 Consultas y Reportes
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="loadPage('views/autor.php')">
                        👤 Autor
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" onclick="confirmarSalida()">
                        🚪 Salir
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Área de Contenido -->
        <div class="content-area">
            <div id="main-content">
                <div class="welcome-screen">
                    <h2>Bienvenido al Sistema de Gestión Hospitalaria</h2>
                    <p>Seleccione una opción del menú para comenzar</p>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3 id="total-pacientes">0</h3>
                            <p>Pacientes Registrados</p>
                        </div>
                        <div class="stat-card">
                            <h3 id="total-doctores">0</h3>
                            <p>Doctores Activos</p>
                        </div>
                        <div class="stat-card">
                            <h3 id="total-citas">0</h3>
                            <p>Citas Programadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
    <script>
        // Cargar estadísticas al iniciar
        window.onload = function() {
            cargarEstadisticas();
        };

        function cargarEstadisticas() {
            fetch('controllers/estadisticas.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-pacientes').textContent = data.pacientes || 0;
                    document.getElementById('total-doctores').textContent = data.doctores || 0;
                    document.getElementById('total-citas').textContent = data.citas || 0;
                });
        }
    </script>
</body>
</html>
