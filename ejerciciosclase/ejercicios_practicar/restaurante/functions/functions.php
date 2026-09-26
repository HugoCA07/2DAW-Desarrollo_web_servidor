<?php

function obtenerPedidosPendientes(string $rutaCsv)
{
    if (!file_exists($rutaCsv) || !is_readable($rutaCsv)) {
        return [];
    }

    $stream = fopen($rutaCsv, 'r');

    if ($stream === false) {
        return [];
    }

    $pedidos_pendientes = [];
    $encabezado = fgetcsv($stream);

    if ($encabezado !== false) {
        $numcampos = count($encabezado);

        while (($fila = fgetcsv($stream)) !== false) {
            if (count($fila) === $numcampos) {
                $pedido = array_combine($encabezado, $fila);
                if (isset($pedido['Estado']) && (string)$pedido['Estado'] === 'Pendiente') {
                    $precioBase = (float)($pedido['Precio_Base'] ?? 0);
                    $descuento = (float)($pedido['Descuento_Porcentaje'] ?? 0);

                    $pedido['Precio_Final'] = round($precioBase - ($precioBase * ($descuento / 100)), 2);
                    $pedidos_pendientes[] = $pedido;
                }
            }
        }
    }

    fclose($stream);

    return $pedidos_pendientes;
}
