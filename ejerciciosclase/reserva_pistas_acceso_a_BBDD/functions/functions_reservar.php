<?php

// CORRECCIÓN: Creamos la función e() para cumplir el requisito "Toda salida con e()" y evitar ataques XSS.
function e($str)
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// CORRECCIÓN: Definimos las pistas, horas y materiales en Arrays (Requisito de la rúbrica)
function getPistas()
{
    return ['Pádel 1', 'Pádel 2', 'Tenis', 'Baloncesto'];
}
function getHoras()
{
    return ['16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
}
function getMateriales()
{
    return ['raquetas', 'balon', 'petos'];
}

// CORRECCIÓN: Centralizamos la creación del Hash para no repetir código.
function generarHashReserva($fecha, $hora, $pista)
{
    return hash("murmur3a", ($fecha . '_' . $hora . '_' . $pista));
}

function validarMateriales(string $material)
{
    // CORRECCIÓN: Validamos usando el array centralizado
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
        $email  = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '');
        $pista  = trim(filter_input(INPUT_POST, 'pista', FILTER_UNSAFE_RAW) ?? '');
        $fecha  = trim(filter_input(INPUT_POST, 'fecha', FILTER_UNSAFE_RAW) ?? '');
        $hora   = trim(filter_input(INPUT_POST, 'hora', FILTER_UNSAFE_RAW) ?? '');
        $materiales = filter_input(
            INPUT_POST,
            'material',
            FILTER_CALLBACK,
            [
                'options' => 'validarMateriales',
                'flags'   => FILTER_REQUIRE_ARRAY
            ]
        );

        // CORRECCIÓN: Comprobamos que el nombre tenga entre 2 y 60 caracteres
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

        // CORRECCIÓN: Validamos que la pista exista en nuestro Array
        if ($pista === "" || !in_array($pista, getPistas())) {
            $resultado = false;
            $errores[] = "Debes seleccionar una pista válida.";
        } else {
            $valores_reserva['pista'] = $pista;
        }

        // CORRECCIÓN: Validamos que la fecha sea válida, a partir de hoy y máximo 14 días.
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

        // CORRECCIÓN: Validamos que la hora exista en nuestro Array
        if ($hora === "" || !in_array($hora, getHoras())) {
            $resultado = false;
            $errores[] = "La hora de la reserva es obligatoria o no es válida.";
        } else {
            $valores_reserva['hora'] = $hora;
        }

        $materialesFiltrados = array_filter((array)$materiales);

        if (!empty($materialesFiltrados)) {
            $valores_reserva['material'] = implode(', ', $materialesFiltrados);
            // CORRECCIÓN: Guardamos el array original para marcar los checkboxes como marcados si hay error
            $valores_reserva['material_array'] = $materialesFiltrados;
        } else {
            $valores_reserva['material'] = '';
            $valores_reserva['material_array'] = [];
        }

        // CORRECCIÓN: Capturamos la validación de normas, era obligatoria
        $normas = filter_input(INPUT_POST, 'normas', FILTER_UNSAFE_RAW);
        if (!$normas) {
            $resultado = false;
            $errores[] = "Debes aceptar las normas.";
        }

        if ($resultado) {
            $linea_csv = [
                $valores_reserva['nombre']   ?? '',
                $valores_reserva['email']    ?? '',
                $valores_reserva['pista']    ?? '',
                $valores_reserva['fecha']    ?? '',
                $valores_reserva['hora']     ?? '',
                $valores_reserva['material'] ?? '',
                'Confirmada'
            ];

            // CORRECCIÓN: Usamos la función refactorizada
            $hashreserva = generarHashReserva($valores_reserva['fecha'], $valores_reserva['hora'], $valores_reserva['pista']);
            $ruta_csv = __DIR__ . '/../data/reservas.csv';

            if (reservaExiste($ruta_csv, $hashreserva)) {
                $errores[] = 'Ya existe una reserva para esa pista en la fecha y hora seleccionadas.';
                $resultado = false;
            } else {
                guardarReserva($linea_csv, $ruta_csv);
                // CORRECCIÓN: PRG estricto pidiendo el código 303 en la cabecera
                header('Location: confirmacion.php?hashreserva=' . $hashreserva, true, 303);
                exit;
            }
        }

        if (!$resultado && !empty($errores)) {
            // CORRECCIÓN: Respondemos con el código HTTP 422 estipulado en la rúbrica si falla la validación
            http_response_code(422);
            echo '<h3>Se han encontrado los siguientes errores:</h3>';
            echo '<ul>';
            foreach ($errores as $error) {
                // CORRECCIÓN: Escapamos los errores visualizados
                echo '<li>' . e($error) . '</li>';
            }
            echo '</ul>';
        }
    }

    // CORRECCIÓN: Retornamos los valores para pasarlos a la vista y hacerlos persistentes
    return ['resultado' => $resultado, 'valores' => $valores_reserva, 'errores' => $errores];
}

function guardarReserva(array $valores_reserva, string $csv_ruta)
{
    $stream = fopen($csv_ruta, 'a');
    fputcsv($stream, $valores_reserva, ',');
    fclose($stream);
}

function reservaExiste(string $rutaCSV, string $hashreserva)
{
    if (!file_exists($rutaCSV) || ($stream = fopen($rutaCSV, 'r')) === false) {
        return false;
    }

    while (($fila = fgetcsv($stream)) !== false) {
        if (isset($fila[2], $fila[3], $fila[4])) {
            // CORRECCIÓN: Usamos la función centralizada en lugar del string crudo
            $hashFila = generarHashReserva($fila[3], $fila[4], $fila[2]);
            if ($hashFila === $hashreserva) {
                fclose($stream);
                return true;
            }
        }
    }

    fclose($stream);
    return false;
}
