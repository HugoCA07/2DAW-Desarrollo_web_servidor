<?php

/**
 * Crea un objeto PDO para la conexión
 * @return PDO|string Devuelve o el dbhandler o la cadena de error
 */
function conexion(): PDO|string
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
    } catch (PDOException $e) {
        echo $e->getMessage();
        return "No se puede conectar con la base de datos";
    }
}

/**
 * @param PDO $pdo recibe un dbhandler
 * @return int devuelve los libros disponibles
 */
function contarDisponibles(PDO $pdo): int
{
    $consulta = $pdo->prepare("SELECT COUNT(*) FROM libros WHERE disponible = 1");
    $consulta->execute();
    return $consulta->fetchColumn();
}

/**
 * @param PDO $pdo
 * @param int $id
 * @return array|false
 * Devuelve un libro en especifico
 */
function obtenerlibro(PDO $pdo,int $id): array|false
{
    $consulta = $pdo->prepare("SELECT * FROM libros WHERE id = ?");
    $consulta->execute([$id]);
    $resultado = $consulta->fetch();
    return $resultado ?? false;
}

/**
 * @param PDO $pdo
 * @param string|null $genero
 * @return array
 * Lista todos los libros con un filtro para genero
 */
function listarLibros(PDO $pdo, ?string $genero = null): array
{
    if (!empty($genero)) {
        if ($genero === "Todos") {
            $consulta = $pdo->prepare("SELECT * FROM libros");
            $consulta->execute();
        } else {
            $consulta = $pdo->prepare("SELECT * FROM libros WHERE genero = ?");
            $consulta->execute([$genero]);
        }
    } else {
        $consulta = $pdo->prepare("SELECT * FROM libros ORDER BY titulo");
        $consulta->execute();
    }
    return $consulta->fetchAll();
}

/**
 * @return false|array
 * Lee los inputs del usuario de la URL
 */
function leerInputs(): false|array
{
    $inputs = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
    if ($inputs === null) {
        return false;
    } else {
        return $inputs;
    }
}

// TODO: Hacer la función de meter libro
//function insertarLibro(PDO $pdo, array $datos): int
//{
//
//}