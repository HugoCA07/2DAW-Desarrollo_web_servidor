-- ============================================================
-- Actividad: cuestionario con progreso en sesión · cuestionario.sql
-- Ejecutar después de usuarios.sql sobre login_dwes.
-- Se puede ejecutar varias veces sin errores.
-- ============================================================

USE login_dwes;

-- Borramos primero resultados por la clave ajena hacia preguntas/usuarios
DROP TABLE IF EXISTS resultados;
DROP TABLE IF EXISTS preguntas;

-- Tabla de preguntas tipo test
CREATE TABLE preguntas (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enunciado VARCHAR(255) NOT NULL,
    opcion_a  VARCHAR(255) NOT NULL,
    opcion_b  VARCHAR(255) NOT NULL,
    opcion_c  VARCHAR(255) NOT NULL,
    opcion_d  VARCHAR(255) NOT NULL,
    correcta  CHAR(1) NOT NULL,
    CONSTRAINT chk_preguntas_correcta CHECK (correcta IN ('a', 'b', 'c', 'd'))
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- Tabla de resultados de cuestionarios completados
CREATE TABLE resultados (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id      INT UNSIGNED NOT NULL,
    aciertos        TINYINT UNSIGNED NOT NULL,
    total_preguntas TINYINT UNSIGNED NOT NULL,
    fecha           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resultados_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- 12 preguntas de prueba sobre DWES (sesiones, PDO, formularios)
INSERT INTO preguntas (enunciado, opcion_a, opcion_b, opcion_c, opcion_d, correcta) VALUES
('¿Qué función se utiliza en PHP para iniciar o reanudar una sesión?',
 'session_open()', 'session_start()', 'session_begin()', 'start_session()', 'b'),

('¿Dónde se almacenan por defecto los datos de $_SESSION?',
 'En el navegador del cliente en texto plano', 'En una cookie cifrada', 'En el servidor', 'En la memoria caché del router', 'c'),

('¿Qué función regenera el identificador de sesión para evitar la fijación de sesión?',
 'session_new_id()', 'session_regenerate_id(true)', 'session_reset_id()', 'session_refresh()', 'b'),

('¿Qué función debe ejecutarse siempre inmediatamente después de header("Location: ...")?',
 'die_header()', 'break;', 'exit;', 'return true;', 'c'),

('¿Qué método de PDO se utiliza para preparar una consulta SQL con parámetros?',
 '$pdo->query()', '$pdo->prepare()', '$pdo->exec()', '$pdo->statement()', 'b'),

('¿Qué atributo de PDO hace que los errores de base de datos lancen excepciones?',
 'PDO::ERRMODE_EXCEPTION', 'PDO::ERRMODE_WARNING', 'PDO::THROW_ERRORS', 'PDO::ATTR_EXCEPTIONS', 'a'),

('¿Qué función de PHP se usa para verificar una contraseña frente a su hash?',
 'password_check()', 'password_compare()', 'password_verify()', 'hash_equals_pass()', 'c'),

('¿Qué función previene ataques XSS al imprimir texto procedente del usuario en HTML?',
 'strip_tags_all()', 'htmlspecialchars()', 'mysqli_real_escape_string()', 'urlencode()', 'b'),

('¿Qué código de estado HTTP se suele usar en el patrón PRG tras procesar un POST?',
 '200 OK', '404 Not Found', '303 See Other (o 302)', '500 Internal Server Error', 'c'),

('¿Cuál es la forma correcta de validar un email en PHP?',
 'FILTER_VALIDATE_EMAIL === $email', 'filter_var($email, FILTER_VALIDATE_EMAIL)', 'validate_email($email)', 'is_email($email)', 'b'),

('¿Qué superglobal recoge los datos enviados mediante un formulario con method="POST"?',
 '$_REQUEST_POST', '$_FORM', '$_POST', '$_DATA', 'c'),

('¿Qué ocurre si llamamos a session_destroy() sin vaciar $_SESSION ni borrar la cookie?',
 'Se borra todo automáticamente en cliente y servidor', 'Los datos en memoria de $_SESSION siguen disponibles en esa petición y la cookie persiste', 'Lanza un Fatal Error', 'Cierra el navegador del usuario', 'b');