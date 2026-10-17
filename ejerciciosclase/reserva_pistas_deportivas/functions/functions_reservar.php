<?php

function validarMateriales(string $material)
{
    $materialesValidos = ['raquetas', 'balon', 'petos'];
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

        if ($nombre === "") {
            $resultado = false;
            $errores[] = "El campo nombre es obligatorio.";
        } else {
            $valores_reserva['nombre'] = $nombre;
        }

        if (!$email) {
            $resultado = false;
            $errores[] = "El correo electrónico no es válido o está vacío.";
        } else {
            $valores_reserva['email'] = $email;
        }

        if ($pista === "") {
            $resultado = false;
            $errores[] = "Debes seleccionar una pista.";
        } else {
            $valores_reserva['pista'] = $pista;
        }

        if ($fecha === "") {
            $resultado = false;
            $errores[] = "La fecha de la reserva es obligatoria.";
        } else {
            $valores_reserva['fecha'] = $fecha;
        }

        if ($hora === "") {
            $resultado = false;
            $errores[] = "La hora de la reserva es obligatoria.";
        } else {
            $valores_reserva['hora'] = $hora;
        }

        $materialesFiltrados = array_filter((array)$materiales);

        if (!empty($materialesFiltrados)) {
            $valores_reserva['material'] = implode(', ', $materialesFiltrados);
        } else {
            $valores_reserva['material'] = '';
        }

        if ($resultado) {
            $linea_csv = [
                $valores_reserva['nombre']   ?? '',
                $valores_reserva['email']    ?? '',
                $valores_reserva['pista']    ?? '',
                $valores_reserva['fecha']    ?? '',
                $valores_reserva['hora']     ?? '',
                $valores_reserva['material'] ?? '',
                $valores_reserva['normas']   ?? 'Confirmada'
            ];

            $hashreserva = hash("murmur3a", ($valores_reserva['fecha'] . '_' . $valores_reserva['hora'] . '_' . $valores_reserva['pista']));
            $ruta_csv = __DIR__ . '/../data/reservas.csv';

            if (reservaExiste($ruta_csv, $hashreserva)) {
                echo '<h3>Error: Ya existe una reserva para esa pista en la fecha y hora seleccionadas.</h3>';
                $resultado = false;
            } else {
                guardarReserva($linea_csv, $ruta_csv);
                header('Location: confirmacion.php?hashreserva=' . $hashreserva);
                exit;
            }
        }

        if (!$resultado && !empty($errores)) {
            echo '<h3>Se han encontrado los siguientes errores:</h3>';
            echo '<ul>';
            foreach ($errores as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul>';
        }
    }

    return $resultado;
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
            $hashFila = hash("murmur3a", ($fila[3] . '_' . $fila[4] . '_' . $fila[2]));
            if ($hashFila === $hashreserva) {
                fclose($stream);
                return true;
            }
        }
    }

    fclose($stream);
    return false;
}
