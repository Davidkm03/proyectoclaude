// Sistema de Gestión Hospitalaria - JavaScript Principal

// Función para cargar páginas en el contenedor principal
function loadPage(page) {
    const mainContent = document.getElementById('main-content');

    // Mostrar loading
    mainContent.innerHTML = '<div class="loading-container" style="text-align: center; padding: 50px;"><div class="loading"></div><p>Cargando...</p></div>';

    // Cargar página con fetch
    fetch(page)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar la página');
            }
            return response.text();
        })
        .then(html => {
            mainContent.innerHTML = html;

            // Ejecutar scripts en el contenido cargado
            const scripts = mainContent.querySelectorAll('script');
            scripts.forEach(script => {
                const newScript = document.createElement('script');
                if (script.src) {
                    newScript.src = script.src;
                } else {
                    newScript.textContent = script.textContent;
                }
                document.body.appendChild(newScript);
                document.body.removeChild(newScript);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            mainContent.innerHTML = `
                <div style="text-align: center; padding: 50px; color: #f44336;">
                    <h2>⚠️ Error al cargar la página</h2>
                    <p>${error.message}</p>
                    <button class="btn btn-primary" onclick="location.reload()">Recargar</button>
                </div>
            `;
        });
}

// Función para toggle de submenús
function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    if (submenu) {
        submenu.classList.toggle('active');
    }
}

// Función para confirmar salida
function confirmarSalida() {
    if (confirm('¿Está seguro que desea salir del sistema?')) {
        // Limpiar datos de sesión si los hay
        sessionStorage.clear();

        // Mostrar mensaje de despedida
        const mainContent = document.getElementById('main-content');
        mainContent.innerHTML = `
            <div style="text-align: center; padding: 100px;">
                <h2>👋 Hasta luego</h2>
                <p>Gracias por usar el Sistema de Gestión Hospitalaria</p>
                <p style="margin-top: 20px;">
                    <button class="btn btn-primary" onclick="location.reload()">Volver al Sistema</button>
                </p>
            </div>
        `;
    }
}

// Función para validar campos vacíos
function validarCampoVacio(valor, nombreCampo) {
    if (!valor || valor.trim() === '') {
        return `El campo ${nombreCampo} es requerido`;
    }
    return null;
}

// Función para validar email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(email)) {
        return 'Email inválido';
    }
    return null;
}

// Función para validar teléfono
function validarTelefono(telefono) {
    const regex = /^[0-9\-\+\(\)\s]+$/;
    if (!regex.test(telefono)) {
        return 'Teléfono inválido';
    }
    return null;
}

// Función para validar edad
function validarEdad(edad) {
    const edadNum = parseInt(edad);
    if (isNaN(edadNum) || edadNum < 0 || edadNum > 150) {
        return 'La edad debe ser un número entre 0 y 150';
    }
    return null;
}

// Función para validar fecha
function validarFecha(fecha) {
    const fechaObj = new Date(fecha);
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    if (isNaN(fechaObj.getTime())) {
        return 'Fecha inválida';
    }

    if (fechaObj < hoy) {
        return 'La fecha no puede ser anterior a hoy';
    }

    return null;
}

// Función para formatear fecha
function formatearFecha(fecha) {
    const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'info') {
    const colores = {
        success: '#4CAF50',
        error: '#f44336',
        warning: '#FF9800',
        info: '#2196F3'
    };

    const notificacion = document.createElement('div');
    notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colores[tipo]};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 10000;
        animation: slideIn 0.3s ease-in-out;
    `;
    notificacion.textContent = mensaje;

    document.body.appendChild(notificacion);

    setTimeout(() => {
        notificacion.style.animation = 'slideOut 0.3s ease-in-out';
        setTimeout(() => {
            document.body.removeChild(notificacion);
        }, 300);
    }, 3000);
}

// Estilos para animaciones de notificaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Función para confirmar eliminación
function confirmarEliminacion(mensaje = '¿Está seguro de eliminar este registro?') {
    return confirm(mensaje);
}

// Función para cargar estadísticas
function cargarEstadisticas() {
    fetch('controllers/estadisticas.php')
        .then(response => response.json())
        .then(data => {
            if (document.getElementById('total-pacientes')) {
                document.getElementById('total-pacientes').textContent = data.pacientes || 0;
            }
            if (document.getElementById('total-doctores')) {
                document.getElementById('total-doctores').textContent = data.doctores || 0;
            }
            if (document.getElementById('total-citas')) {
                document.getElementById('total-citas').textContent = data.citas || 0;
            }
        })
        .catch(error => console.error('Error al cargar estadísticas:', error));
}

// Función para sanitizar HTML (prevenir XSS)
function sanitizarHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Función para debounce (optimizar búsquedas)
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Prevenir envío duplicado de formularios
let formSubmitting = false;

function preveniEnvioDuplicado(form) {
    if (formSubmitting) {
        return false;
    }
    formSubmitting = true;
    setTimeout(() => {
        formSubmitting = false;
    }, 2000);
    return true;
}

// Manejo de errores globales
window.onerror = function(msg, url, lineNo, columnNo, error) {
    console.error('Error: ', msg, '\nURL: ', url, '\nLine: ', lineNo, '\nColumn: ', columnNo, '\nError: ', error);
    return false;
};

// Event listener para cerrar modales con ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modales = document.querySelectorAll('.modal');
        modales.forEach(modal => {
            if (modal.style.display === 'block') {
                modal.style.display = 'none';
            }
        });
    }
});

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de Gestión Hospitalaria iniciado');

    // Cargar estadísticas si estamos en la página principal
    if (document.getElementById('total-pacientes')) {
        cargarEstadisticas();
        // Actualizar cada 30 segundos
        setInterval(cargarEstadisticas, 30000);
    }
});
