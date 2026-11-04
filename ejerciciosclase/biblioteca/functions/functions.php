<?php

declare(strict_types=1);

// Géneros válidos. Los usan el filtro del listado y el formulario de nuevo libro.
const GENEROS = ['Novela', 'Teatro', 'Ensayo', 'Poesía', 'Cómic'];

/**
 * Crea un objeto PDO para la conexión
 * @return PDO Devuelve el dbhandler
 * CAMBIO: antes devolvía un string si fallaba, y eso rompía todas las funciones tipadas con PDO.
 * Ahora si no hay conexión se corta aquí con un mensaje genérico (sin enseñar el error interno).
 */
function conexion(): PDO
{
    try {
        return new PDO(
            'mysql:host=localhost;dbname=biblioteca;charset=utf8mb4',
            'dwes',
            'abc123.',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException) {
        // El detalle del error (usuario, host...) no se le enseña al usuario
        http_response_code(500);
        exit("No se puede conectar con la base de datos");
    }
}

/**
 * @param PDO $pdo recibe un dbhandler
 * @return int devuelve los libros disponibles
 */
function contarDisponibles(PDO $pdo): int
{
    return (int)$pdo->query("SELECT COUNT(*) FROM libros WHERE disponible = 1")->fetchColumn();
}

/**
 * @param PDO $pdo
 * @param int $id
 * @return array|false
 * Devuelve un libro en especifico
 * CAMBIO: fetch() ya devuelve false si no hay fila, así que el "?? false" sobraba.
 */
function obtenerlibro(PDO $pdo, int $id): array|false
{
    $consulta = $pdo->prepare("SELECT * FROM libros WHERE id = ?");
    $consulta->execute([$id]);
    return $consulta->fetch();
}

/**
 * @param PDO $pdo
 * @param string|null $genero
 * @return array
 * Lista todos los libros con un filtro para genero
 * CAMBIO: las dos ramas ordenan por título, y un género que no existe devuelve lista vacía.
 */
function listarLibros(PDO $pdo, ?string $genero = null): array
{
    if (empty($genero) || $genero === "Todos") {
        $consulta = $pdo->query("SELECT * FROM libros ORDER BY titulo");
    } else {
        $consulta = $pdo->prepare("SELECT * FROM libros WHERE genero = ? ORDER BY titulo");
        $consulta->execute([$genero]);
    }
    return $consulta->fetchAll();
}

/**
 * @return false|array
 * Lee los inputs del usuario de la URL
 */
function leerInputs(): false|array
{
    $inputs = filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW);
    return $inputs ?? false;
}

/**
 * @param array $inputs los inputs de la URL (puede estar vacío)
 * @return string el género elegido, o "Todos" si no hay o no es válido
 * NUEVA: así index.php no depende de que exista $inputs['genero'] (evita el warning).
 */
function obtenerGeneroFiltro(array $inputs): string
{
    $genero = $inputs['genero'] ?? 'Todos';
    return ($genero === 'Todos' || in_array($genero, GENEROS, true)) ? $genero : 'Todos';
}

/**
 * @param array $post los datos crudos del formulario ($_POST)
 * @return array [datos limpios, errores]. Si hay errores, los datos no se deben insertar
 * NUEVA: valida y limpia los 5 campos del libro. Solo deja pasar las claves que se esperan.
 */
function validarLibro(array $post): array
{
    $errores = [];

    $isbn = trim((string)($post['isbn'] ?? ''));
    $titulo = trim((string)($post['titulo'] ?? ''));
    $autor = trim((string)($post['autor'] ?? ''));
    $genero = trim((string)($post['genero'] ?? ''));
    $anio = filter_var($post['anio'] ?? '', FILTER_VALIDATE_INT);

    // Según la BBDD: isbn VARCHAR(17), titulo VARCHAR(150), autor VARCHAR(100)
    if ($isbn === '' || mb_strlen($isbn) > 17) {
        $errores[] = "El ISBN es obligatorio y tiene como máximo 17 caracteres.";
    }
    if ($titulo === '' || mb_strlen($titulo) > 150) {
        $errores[] = "El título es obligatorio y tiene como máximo 150 caracteres.";
    }
    if ($autor === '' || mb_strlen($autor) > 100) {
        $errores[] = "El autor es obligatorio y tiene como máximo 100 caracteres.";
    }
    // anio es SMALLINT, y un año futuro no tiene sentido
    if ($anio === false || $anio < 0 || $anio > (int)date('Y')) {
        $errores[] = "El año no es válido.";
    }
    if (!in_array($genero, GENEROS, true)) {
        $errores[] = "El género no es válido.";
    }

    $datos = [
        ':isbn' => $isbn,
        ':titulo' => $titulo,
        ':autor' => $autor,
        ':anio' => $anio,
        ':genero' => $genero,
    ];

    return [$datos, $errores];
}

/**
 * @param PDO $pdo
 * @param array $datos los datos YA validados con validarLibro()
 * @return int (Ultimo id de libro, -1 si el isbn ya existe o -2 si falló otra cosa)
 * Mete un libro nuevo en la base de datos con disponibilidad 1, en caso de que no haya fallos
 * CAMBIO: ya no hace echo. Distingue el isbn repetido (error 1062) de cualquier otro fallo.
 */
function insertarLibro(PDO $pdo, array $datos): int
{
    try {
        $sqlString = "INSERT INTO libros (isbn, titulo, autor, anio, genero) VALUES (:isbn, :titulo, :autor, :anio, :genero)";
        $stmt = $pdo->prepare($sqlString);
        $stmt->execute($datos);

        return (int)$pdo->lastInsertId();

    } catch (PDOException $e) {
        // 1062 = clave duplicada (el isbn es UNIQUE)
        return ($e->errorInfo[1] === 1062) ? -1 : -2;
    }
}

/**
 * @param PDO $pdo
 * @param int $id
 * @param int $disponible
 * @return int es el numero de filas afectadas
 * Sirve para cambiar disponibilidad de libros
 * OJO: si el libro ya tenía ese estado, MySQL devuelve 0 filas aunque no haya error.
 */
function cambiarDisponible(PDO $pdo, int $id, int $disponible): int
{

    $sql = "UPDATE libros SET disponible = :disponible WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':disponible' => $disponible,
        ':id' => $id,
    ]);

    return $stmt->rowCount();
}

/**
 * @param PDO $pdo
 * @param int $id
 * @return int 1 si se borró, 0 si no existe, -1 si tiene préstamos (error 1451), -2 si falla otra cosa
 * Borra un libro. Es la BBDD la que protege los libros con préstamos mediante la clave foránea.
 * CAMBIO: antes se miraba "disponible", que no tiene que ver. Ahora se hace el DELETE
 * directamente y se interpreta el código de error.
 */
function borrarLibro(PDO $pdo, int $id): int
{
    try {
        $stmt = $pdo->prepare("DELETE FROM libros WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return ($stmt->rowCount() === 1) ? 1 : 0;

    } catch (PDOException $e) {
        // 1451 = no se puede borrar porque otra tabla (prestamos) apunta a este libro
        return ($e->errorInfo[1] === 1451) ? -1 : -2;
    }
}