<?php

include(__DIR__.'/src/functions.php');

// Código de práctica para ejercicios en clase

// =====================================================================

// *** Funciones de arrays utiles ***

// array_column()
// array_combine()
// array_is()
// array_filter()
// array_map()
// array_shift()
// array_pop()
// array_values()
// array_walk()
// in_array()
// reset()

// =====================================================================

// Array de prueba
$coches = array(
	array(
		'marca' => 'Toyota',
		'modelo' => 'Corolla',
		'color' => 'Rojo',
		'matricula' => '1234 ABC'
	),
	array(
		'marca' => 'Ford',
		'modelo' => 'Focus',
		'color' => 'Azul',
		'matricula' => '2345 BCD'
	),
	array(
		'marca' => 'Seat',
		'modelo' => 'Ibiza',
		'color' => 'Blanco',
		'matricula' => '3456 CDE'
	),
	array(
		'marca' => 'Volkswagen',
		'modelo' => 'Golf',
		'color' => 'Negro',
		'matricula' => '4567 DEF'
	),
	array(
		'marca' => 'Renault',
		'modelo' => 'Clio',
		'color' => 'Gris',
		'matricula' => '5678 EFG'
	),
	array(
		'marca' => 'Peugeot',
		'modelo' => '208',
		'color' => 'Verde',
		'matricula' => '6789 FGH'
	),
	array(
		'marca' => 'BMW',
		'modelo' => 'Serie 3',
		'color' => 'Plateado',
		'matricula' => '7890 GHI'
	),
	array(
		'marca' => 'Audi',
		'modelo' => 'A3',
		'color' => 'Azul',
		'matricula' => '8901 HIJ'
	),
	array(
		'marca' => 'Mercedes-Benz',
		'modelo' => 'Clase A',
		'color' => 'Blanco',
		'matricula' => '9012 IJK'
	),
	array(
		'marca' => 'Hyundai',
		'modelo' => 'i30',
		'color' => 'Amarillo',
		'matricula' => '0123 JKL'
	)
);

// =====================================================================

// *** Buscar por color ***

// $colorBuscado = 'Blanco';

// foreach ($coches as $coche) {
// 	if ($coche['color'] === $colorBuscado) {
// 		dump($coche);
// 	}
// }


// =====================================================================

// *** Volcar a nuevo array con indice de matricula ***

// $nuevocoches = array();
// foreach ($coches as $coche) {
// 	$nuevocoches[$coche["matricula"]] = $coche;
// }

// dump($nuevocoches);

// =====================================================================

// *** Crear un archivo CSV con los datos de los coches ***
// *** Y leer los datos de coches de un color en concreto desde el archivo CSV ***

// Meter datos en CSV
// $archivo = fopen("coches.csv", "w");

// foreach ($coches as $coche){
// 	fputcsv($archivo, $coche);
// }

// fclose($archivo);

// // Leer datos de coches de un color en concreto desde el archivo CSV
// $archivo = fopen("coches.csv", "r");

// $colorBuscado = 'Blanco';

// if($archivo){
// 	while(($linea = fgetcsv($archivo,1000,",")) !== false){
// 		if($linea[2] === $colorBuscado){
// 			dump($linea);
// 		}
// 	}
// }

// $archivo = fclose($archivo);

// =====================================================================

// *** SOLUCIÓN DEL PROFE ***

// if (($fd = fopen('./data_source/coches.csv','r')) !== false) {
//   if(($nombre_campos = fgetcsv($fd)) !== false){

//     while(($nuevo_coche = fgetcsv($fd)) !== false){

//       $coches[$nuevo_coche[3]] = array_combine($nombre_campos,$nuevo_coche);
//     }
//     fclose($fd);
//   }
// }

// =====================================================================

// *** File_get_contents() ***

// Leer todo el csv de coches y mostrar el array de coches en pantalla

// $gestor = fopen("coches.csv", "r");
// $coches = array();

// $claves = ['marca', 'modelo', 'color', 'matricula'];

// if ($gestor !== false) {
//     while (($campos = fgetcsv($gestor)) !== false) {
//         if (count($campos) === 4) {
//             $coches[$campos[3]] = array_combine($claves, $campos);
//         }
//     }
//     fclose($gestor);
// }

// dump($coches);

// =====================================================================

$coches = getCSVContentInArray(__DIR__.'/coches.csv', "Matricula");
dump($coches);