<?php

// Dado un array indexado de precios de productos (sin IVA), calcula con un bucle: 

// El precio total (sin usar array_sum, hazlo con un contador acumulador). 
// El precio medio. 
// El precio más caro y el más barato (sin usar max()/min(), comparando manualmente dentro del bucle). 

// Al terminar, repite los tres cálculos usando array_sum(), count(), max() y min(), y comprueba que el resultado coincide. 

$productos = [
    [
        "nombre" => "Camiseta de Algodón",
        "precio" => 19.99
    ],
    [
        "nombre" => "Pantalón Vaquero",
        "precio" => 39.95
    ],
    [
        "nombre" => "Zapatillas Deportivas",
        "precio" => 59.90
    ],
    [
        "nombre" => "Gorra Ajustable",
        "precio" => 14.50
    ],
    [
        "nombre" => "Mochila Impermeable",
        "precio" => 29.99
    ]
];

$precio_total = 0;
$precio_medio = 0;
$precio_caro = PHP_INT_MIN;
$precio_barato = PHP_INT_MAX;

foreach ($productos as $clave => $valor) {
    $precio_total += (float)$valor['precio'];
    $precio_medio += (float)$valor['precio'];

    if ((float)$valor['precio'] > $precio_caro) {
        $precio_caro = (float)$valor['precio'];
    }
    if ((float)$valor['precio'] < $precio_barato) {
        $precio_barato = (float)$valor['precio'];
    }
}

$precio_medio /= count($productos);

echo $precio_total . PHP_EOL;
echo $precio_medio . PHP_EOL;
echo $precio_caro . PHP_EOL;
echo $precio_barato . PHP_EOL;

$precio_total = array_sum(array_column($productos, 'precio'));
$precio_medio = (float)($precio_total / count($productos));
$precio_caro = max(array_column($productos, 'precio'));
$precio_barato = min(array_column($productos, 'precio'));

echo '==============================' . PHP_EOL;
echo $precio_total . PHP_EOL;
echo $precio_medio . PHP_EOL;
echo $precio_caro . PHP_EOL;
echo $precio_barato . PHP_EOL;
