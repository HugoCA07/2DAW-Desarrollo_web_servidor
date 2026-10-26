<?php

// Creamos la función e()
function e($str): string
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Definimos las pistas, horas y materiales en Arrays
function getPistas(): array
{
    return ['Pádel 1', 'Pádel 2', 'Tenis', 'Baloncesto'];
}

function getHoras(): array
{
    return ['16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
}

function getMateriales(): array
{
    return ['raquetas', 'balon', 'petos'];
}

// centralizamos la creación del Hash para no repetir código
function generarHashReserva($fecha, $hora, $pista): string
{
    return hash("murmur3a", ($fecha . '_' . $hora . '_' . $pista));
}

function validarMateriales(string $material): ?string
{
    // Validamos usando el array centralizado
    $materialesValidos = getMateriales();
    return in_array($material, $materialesValidos) ? $material : null;
}

function validarReserva()
{
    $resultado = true;
    $errores = [];
    $valores_reserva = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW) ?? '');
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '');
        $pista = trim(filter_input(INPUT_POST, 'pista', FILTER_UNSAFE_RAW) ?? '');
        $fecha = trim(filter_input(INPUT_POST, 'fecha', FILTER_UNSAFE_RAW) ?? '');
        $hora = trim(filter_input(INPUT_POST, 'hora', FILTER_UNSAFE_RAW) ?? '');
        $materiales = filter_input(
            INPUT_POST,
            'material',
            FILTER_CALLBACK,
            [
                'options' => 'validarMateriales',
                'flags' => FILTER_REQUIRE_ARRAY
            ]
        );

        // comprobamos que el nombre tenga entre 2 y 60 caracteres
        if ($nombre === "" || strlen($nombre) < 2 || strlen($nombre) > 60) {
            $resultado = false;
            $errores[] = "El campo nombre es obligatorio y debe tener entre 2 y 60 caracteres.";
        } else {
            $valores_reserva['nombre'] = $nombre;
        }

        if (!$email) {
            $resultado = false;
            $errores[] = "El correo electrónico no es válido o está vacío.";
        } else {
            $valores_reserva['email'] = $email;
        }

        // validamos que la pista exista en nuestro Array
        if ($pista === "" || !in_array($pista, getPistas())) {
            $resultado = false;
            $errores[] = "Debes seleccionar una pista válida.";
        } else {
            $valores_reserva['pista'] = $pista;
        }

        // validamos que la fecha sea válida, a partir de hoy y máximo 14 días.
        if ($fecha === "") {
            $resultado = false;
            $errores[] = "La fecha de la reserva es obligatoria.";
        } else {
            $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
            $hoy = new DateTime();
            $hoy->setTime(0, 0, 0);
            $limite = (clone $hoy)->modify('+14 days');

            if (!$fecha_obj || $fecha_obj < $hoy || $fecha_obj > $limite) {
                $resultado = false;
                $errores[] = "La fecha debe ser a partir de hoy y como máximo en 14 días.";
            } else {
                $valores_reserva['fecha'] = $fecha;
            }
        }

        // Validamos que la hora exista en nuestro Array
        if ($hora === "" || !in_array($hora, getHoras())) {
            $resultado = false;
            $errores[] = "La hora de la reserva es obligatoria o no es válida.";
        } else {
            $valores_reserva['hora'] = $hora;
        }

        $materialesFiltrados = array_filter((array)$materiales);

        if (!empty($materialesFiltrados)) {
            $valores_reserva['material'] = implode(', ', $materialesFiltrados);
            // guardamos el array original para marcar los checkboxes como marcados si hay error
            $valores_reserva['material_array'] = $materialesFiltrados;
        } else {
            $valores_reserva['material'] = '';
            $valores_reserva['material_array'] = [];
        }

        // capturamos la validación de normas
        $normas = filter_input(INPUT_POST, 'normas', FILTER_UNSAFE_RAW);
        if (!$normas) {
            $resultado = false;
            $errores[] = "Debes aceptar las normas.";
        }

        if ($resultado) {
            $reserva = [
                $valores_reserva['nombre'] ?? '',
                $valores_reserva['email'] ?? '',
                $valores_reserva['pista'] ?? '',
                $valores_reserva['fecha'] ?? '',
                $valores_reserva['hora'] ?? '',
                $valores_reserva['material'] ?? '',
                'Confirmada'
            ];

            // Usamos la función refactorizada
            $hashreserva = generarHashReserva($valores_reserva['fecha'], $valores_reserva['hora'], $valores_reserva['pista']);
            $pdo = [
                'dsn' => 'mysql:host=localhost;dbname=reservapistas;charset=utf8mb4',
                'username' => 'dwes',
                'password' => 'abc123.',
            ];

            if (reservaExiste($pdo, $hashreserva)) {
                $errores[] = 'Ya existe una reserva para esa pista en la fecha y hora seleccionadas.';
                $resultado = false;
            } else {
                guardarReserva($pdo, $reserva);
                // Codigo 303 en la cabecera
                header('Location: confirmacion.php?hashreserva=' . $hashreserva, true, 303);
                exit;
            }
        }

        if (!$resultado && !empty($errores)) {
            // Respondemos con el código HTTP 422 estipulado en la rúbrica si falla la validación
            http_response_code(422);
            echo '<h3>Se han encontrado los siguientes errores:</h3>';
            echo '<ul>';
            foreach ($errores as $error) {
                echo '<li>' . e($error) . '</li>';
            }
            echo '</ul>';
        }
    }

    // Retornamos los valores para pasarlos a la vista y hacerlos persistentes
    return ['resultado' => $resultado, 'valores' => $valores_reserva, 'errores' => $errores];
}

function guardarReserva(array $credBBDD, array $reserva): void
{
    try {
        $pdo = new PDO(
            $credBBDD['dsn'],
            $credBBDD['username'],
            $credBBDD['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        $sqlstring = 'INSERT INTO reservas (nombre, email, pista, date, hora, material, normas) 
                      VALUES (?, ?, ?, ?, ?, ?, ?);';

        $stmt = $pdo->prepare($sqlstring);

        $stmt->execute([
            $reserva[0],
            $reserva[1],
            $reserva[2],
            $reserva[3],
            $reserva[4],
            $reserva[5],
            'Confirmada'
        ]);

    } catch (Exception $e) {
        echo "Error en la base de datos: ";
    }
}


function reservaExiste(array $credBBDD, string $hashreserva): bool
{
    try {
        $pdo = new PDO(
            $credBBDD['dsn'],
            $credBBDD['username'],
            $credBBDD['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );

        $sqlstring = 'SELECT date, hora, pista FROM reservas;';

        $stmt = $pdo->prepare($sqlstring);
        $stmt->execute();
        $valores_consulta = $stmt->fetchAll();

        foreach ($valores_consulta as $valor) {
            $horaFormateada = str_replace('.', ':', $valor['hora']);
            $horaFormateada = substr($horaFormateada, 0, 5);

            $hashreserva2 = generarHashReserva($valor['date'], $horaFormateada, $valor['pista']);
            if ($hashreserva2 === $hashreserva) {
                return true;
            }
        }

        return false;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    return false;
}