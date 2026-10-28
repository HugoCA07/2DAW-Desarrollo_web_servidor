# Cheatsheet PHP — DWES (2º DAW)

> Adaptada a los ejercicios del repo `2DAW-Desarrollo_web_servidor`: arrays, CSV con `fgetcsv`, formularios con `filter_input`, plantillas `.tpl.php`, reserva de pistas (CSV y BBDD) y PDO.

## Índice

1. [Sintaxis básica](#sintaxis)
2. [Depuración y errores](#debug)
3. [Estructuras de control](#control)
4. [Arrays](#arrays)
5. [Funciones de arrays más usadas](#funciones-arrays)
6. [Funciones](#funciones)
7. [Estructura de un proyecto (index + functions + template)](#estructura)
8. [Plantillas `.tpl.php` y sintaxis alternativa](#plantillas)
9. [Escapar la salida: `htmlspecialchars` y `e()`](#escapar)
10. [Formularios: `$_GET`, `$_POST` y `filter_input`](#formularios)
11. [Validación de formularios](#validacion)
12. [Redirecciones y códigos HTTP (PRG)](#http)
13. [Ficheros CSV con `fgetcsv`](#csv)
14. [Otras formas de leer CSV](#csv-otras)
15. [Escribir CSV con `fputcsv`](#csv-escribir)
16. [Base de datos con PDO](#pdo)
17. [Consultas preparadas](#preparadas)
18. [INSERT / UPDATE / DELETE](#insert)
19. [Patrón completo: reservas con PDO](#patron-pdo)
20. [Fechas con `DateTime`](#fechas)
21. [Errores típicos](#errores)
22. [Chuleta express](#express)

---

<a id="sintaxis"></a>
## 1. Sintaxis básica

```php
<?php
echo "Hola mundo";     // imprime
$nombre = "Hugo";      // string
$edad = 20;            // int
$precio = 19.99;       // float
$activo = true;        // bool
$nada = null;
```

- Variables con `$`, distinguen mayúsculas.
- Cada instrucción acaba en `;`.
- Si el fichero solo tiene PHP, no se pone `?>` al final.

### Strings y concatenación

```php
echo "Hola $nombre";          // comillas dobles interpolan
echo 'Hola $nombre';          // simples: literal
echo 'Hola ' . $nombre;       // concatenar con .
$html = '';
$html .= '<ul>';              // .= añade al final
echo 'Total' . PHP_EOL;       // salto de línea

strlen($s);  trim($s);  strtolower($s);  ucfirst($s);
str_replace(':', '', '16:00');      // "1600"
substr('16:00:00', 0, 5);           // "16:00"
str_contains('water1', 'water');    // true (PHP 8)
explode(',', 'a,b,c');              // string → array
implode(', ', ['a', 'b']);          // array → "a, b"
number_format(12.5, 2, ',', '.');   // "12,50"
round(11.256, 2);                   // 11.26
```

### Operadores clave

```php
5 == "5";     // true  (compara valor)
5 === "5";    // false (valor y tipo) ← usar siempre ===
$x ?? 'def';  // null coalescing: si $x no existe o es null
$c ? 'sí' : 'no';   // ternario
(float)$valor; (int)$valor; (string)$valor;   // casting
```

---

<a id="debug"></a>
## 2. Depuración y errores

```php
// Al principio del fichero, para ver todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función dump() que usamos en clase
function dump(mixed $var)
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

var_dump($x);   // tipo + valor
print_r($arr);  // array legible (true = devuelve en vez de imprimir)
```

---

<a id="control"></a>
## 3. Estructuras de control

```php
if ($nota >= 5) {
    echo 'Aprobado';
} elseif ($nota >= 3) {
    echo 'Casi';
} else {
    echo 'Suspenso';
}

for ($i = 0; $i < 5; $i++) { echo $i; }

while (($fila = fgetcsv($stream)) !== false) { /* ... */ }

foreach ($asignaturas as $valor) { echo $valor; }
foreach ($usuario as $clave => $valor) { echo "$clave: $valor"; }

$texto = match ($dia) {
    1 => 'Lunes',
    2 => 'Martes',
    default => 'Otro',
};
```

- `break` sale del bucle, `continue` salta a la siguiente vuelta.

---

<a id="arrays"></a>
## 4. Arrays

```php
// Indexado
$asignaturas = ['DAWES', 'DIWE', 'DAWCLI'];
echo $asignaturas[0];      // DAWES
$asignaturas[] = 'TFG';    // añadir al final

// Asociativo
$usuario = [
    'nombre' => 'Ana',
    'edad'   => 30,
    'activo' => true,
];
echo $usuario['nombre'];

// Multidimensional (array de arrays asociativos) — lo que devuelve un CSV o fetchAll()
$productos = [
    ['nombre' => 'Camiseta', 'precio' => 19.99],
    ['nombre' => 'Gorra',    'precio' => 14.50],
];
echo $productos[1]['nombre'];   // Gorra
```

### Recorrer y acumular (ejercicio de precios)

```php
$total = 0;
$caro = PHP_INT_MIN;
$barato = PHP_INT_MAX;

foreach ($productos as $p) {
    $precio = (float)$p['precio'];
    $total += $precio;
    if ($precio > $caro)   { $caro = $precio; }
    if ($precio < $barato) { $barato = $precio; }
}
$media = $total / count($productos);
```

### Reindexar por un campo (p. ej. matrícula)

```php
$porMatricula = [];
foreach ($coches as $coche) {
    $porMatricula[$coche['matricula']] = $coche;
}
// $porMatricula['1234 ABC'] → datos del coche
```

---

<a id="funciones-arrays"></a>
## 5. Funciones de arrays más usadas

```php
count($a);                           // nº de elementos
in_array('Tenis', $pistas);          // ¿existe el valor? (para validar)
array_key_exists('edad', $u);        // ¿existe la clave?
array_combine($cabecera, $fila);     // une claves + valores → asociativo
array_column($productos, 'precio');  // extrae una columna → [19.99, 14.50]
array_sum([1, 2, 3]);                // 6
max($a);  min($a);
array_map('trim', $campos);          // aplica una función a cada elemento
array_filter($a);                    // quita vacíos/null/false
array_filter($a, fn($x) => $x > 0);  // filtra con condición
array_values($a);                    // reindexa 0,1,2... (tras array_filter)
array_shift($a);                     // saca el PRIMERO (útil para la cabecera)
array_pop($a);                       // saca el último
array_slice($a, 0, 3);               // trozo del array
reset($a);                           // primer elemento
array_keys($a);  array_values($a);
array_merge($a, $b);
sort($a);  ksort($a);  asort($a);
```

### Ejemplos del repo

```php
// Total, máximo y mínimo sin bucle
$precios = array_column($productos, 'precio');
$total = array_sum($precios);
$media = $total / count($productos);
$max = max($precios);

// Filtrar con closure usando variables externas (use)
$filtradas = array_filter($todas, function ($fila) use ($inputs) {
    if (!empty($inputs[0]) && trim($fila[2]) !== trim($inputs[0])) {
        return false;
    }
    return true;
});
$filtradas = array_values($filtradas);   // reindexar
```

---

<a id="funciones"></a>
## 6. Funciones

```php
function saludar(string $nombre): string
{
    return "Hola $nombre";
}

// Parámetro opcional y tipos unión / nullable
function getCSV(string $ruta, string|null $clave = null): array { /* ... */ }
function validarMateriales(string $m): ?string   // ?string = string o null
{
    return in_array($m, getMateriales()) ? $m : null;
}
function guardar(array $datos): void { /* no devuelve nada */ }

// Funciones que devuelven "constantes" del ejercicio
function getPistas(): array
{
    return ['Pádel 1', 'Pádel 2', 'Tenis', 'Baloncesto'];
}

// Función flecha
$doble = fn($n) => $n * 2;
```

- Las variables de fuera **no** se ven dentro de la función: pásalas como parámetro (o `use` en closures).

---

<a id="estructura"></a>
## 7. Estructura de un proyecto (index + functions + template)

Patrón que se repite en todos los ejercicios:

```
proyecto/
├── index.php              ← controlador: obtiene datos y carga la vista
├── functions/
│   └── functions.php      ← lógica (leer CSV, BBDD, validar)
├── data/
│   └── datos.csv / data.sql
└── templates/
    └── index.tpl.php      ← vista: solo HTML + pintar variables
```

```php
// index.php
<?php
require_once __DIR__ . '/functions/functions.php';

$pedidos = obtenerPedidosPendientes(__DIR__ . '/data/pedidos.csv');

require __DIR__ . '/templates/index.tpl.php';   // la plantilla ve $pedidos
```

- `__DIR__` = carpeta del fichero actual → rutas que no fallan.
- `include`: si falla, warning y sigue. `require`: si falla, error fatal.
- `_once`: no lo incluye dos veces (evita "function already declared").
- **Regla**: la lógica (validar, `header()`, códigos HTTP) va **antes** de incluir la plantilla.

---

<a id="plantillas"></a>
## 8. Plantillas `.tpl.php` y sintaxis alternativa

```php
<?php if (!empty($videojuegos)): ?>
    <ul>
        <?php foreach ($videojuegos as $juego): ?>
            <li>
                <strong>Título:</strong> <?= e($juego['Titulo'] ?? '') ?>
                <img src="<?= e($juego['URL_Portada'] ?: $defaultURL) ?>" alt="portada">
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay juegos con stock</p>
<?php endif; ?>
```

- `<?= $x ?>` equivale a `<?php echo $x; ?>`.
- `if (): ... elseif: ... else: ... endif;` / `foreach (): ... endforeach;`.

### Tabla genérica (filas de CSV o BBDD)

```php
<table>
    <?php foreach ($tabla_reservas as $reserva): ?>
        <tr>
            <?php foreach ($reserva as $valor): ?>
                <td><?= e($valor) ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
```

### Generar HTML como string (alternativa)

```php
function getMarkUp(array $data): string
{
    $output = '<ul>';
    foreach ($data as $valor) {
        $output .= '<li>' . e($valor['Marca']) . '</li>';
    }
    $output .= '</ul>';
    return $output;
}
```

### Tablero 2D desde CSV (ejercicio Zelda)

```php
<?php foreach ($tablero as $y => $fila): ?>
    <?php foreach ($fila as $x => $casilla): ?>
        <div class="tile tile-<?= e($casilla) ?>"></div>
    <?php endforeach; ?>
<?php endforeach; ?>
```

---

<a id="escapar"></a>
## 9. Escapar la salida: `htmlspecialchars` y `e()`

Todo dato que venga del usuario, del CSV o de la BBDD se escapa al imprimir (evita XSS).

```php
function e($str): string
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

echo e($nombre);
```

---

<a id="formularios"></a>
## 10. Formularios: `$_GET`, `$_POST` y `filter_input`

```html
<!-- GET: filtros/búsquedas (datos en la URL) -->
<form action="" method="get">
    <select name="pista">...</select>
    <input type="date" name="fecha">
    <button type="submit">Buscar</button>
</form>

<!-- POST: enviar/crear datos -->
<form method="POST">
    <input type="text" name="nombre" required>
    <input type="checkbox" name="material[]" value="raquetas">   <!-- [] = array -->
    <input type="radio" name="hora" value="16:00">
</form>
```

### `filter_input`

```php
$pista = filter_input(INPUT_GET, 'pista');                         // string, null si no existe
$x     = filter_input(INPUT_GET, 'link_pos_x', FILTER_VALIDATE_INT); // int, false si no es int
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);   // false si no es email
$nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW) ?? '');

// Array (checkboxes name="material[]") validando cada elemento con una función
$materiales = filter_input(INPUT_POST, 'material', FILTER_CALLBACK, [
    'options' => 'validarMateriales',
    'flags'   => FILTER_REQUIRE_ARRAY,
]);
```

| Devuelve | Significado |
|----------|-------------|
| `null` | El campo no venía en la petición |
| `false` | Venía pero no pasa el filtro |
| valor | Correcto |

```php
// Comprobación típica (Zelda)
if ($x === null || $x === false || $x < 0 || $x >= $columnas) {
    $x = 0;
}

// ¿Se ha enviado el formulario?
if ($_SERVER['REQUEST_METHOD'] === 'POST') { /* validar */ }
```

### Persistencia (mantener lo que escribió el usuario si hay error)

```php
<input type="text" name="nombre" value="<?= e($valores_previos['nombre'] ?? '') ?>">

<option value="<?= e($p) ?>" <?= ($valores_previos['pista'] ?? '') === $p ? 'selected' : '' ?>>

<input type="radio" name="hora" value="<?= e($h) ?>" <?= ($valores_previos['hora'] ?? '') === $h ? 'checked' : '' ?>>

<input type="checkbox" name="material[]" value="<?= e($m) ?>"
    <?= in_array($m, $valores_previos['material_array'] ?? []) ? 'checked' : '' ?>>
```

### Opciones generadas desde un array

```php
<?php foreach (getPistas() as $p): ?>
    <option value="<?= e($p) ?>"><?= e($p) ?></option>
<?php endforeach; ?>
```

### Enlaces con parámetros GET

```php
<a href="index.php?link_pos_x=<?= $x ?>&link_pos_y=<?= $y - 1 ?>">Arriba</a>
```

---

<a id="validacion"></a>
## 11. Validación de formularios

Patrón: `$errores[]` + `$valores` + `$resultado`.

```php
function validarReserva(): array
{
    $resultado = true;
    $errores = [];
    $valores = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW) ?? '');
        $email  = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $pista  = trim(filter_input(INPUT_POST, 'pista', FILTER_UNSAFE_RAW) ?? '');

        // Longitud
        if (strlen($nombre) < 2 || strlen($nombre) > 60) {
            $resultado = false;
            $errores[] = 'El nombre debe tener entre 2 y 60 caracteres.';
        } else {
            $valores['nombre'] = $nombre;
        }

        // Email
        if (!$email) {
            $resultado = false;
            $errores[] = 'Email no válido.';
        } else {
            $valores['email'] = $email;
        }

        // Valor permitido (lista blanca)
        if (!in_array($pista, getPistas())) {
            $resultado = false;
            $errores[] = 'Pista no válida.';
        } else {
            $valores['pista'] = $pista;
        }

        // Checkbox obligatorio
        if (!filter_input(INPUT_POST, 'normas')) {
            $resultado = false;
            $errores[] = 'Debes aceptar las normas.';
        }

        // Checkboxes múltiples → string para guardar
        $mats = array_filter((array)$materiales);
        $valores['material'] = implode(', ', $mats);
    }

    return ['resultado' => $resultado, 'valores' => $valores, 'errores' => $errores];
}
```

```php
// Mostrar errores
<?php foreach ($errores as $error): ?>
    <li><?= e($error) ?></li>
<?php endforeach; ?>
```

---

<a id="http"></a>
## 12. Redirecciones y códigos HTTP (PRG)

**PRG** (Post/Redirect/Get): tras guardar un POST correcto, redirigir con 303 para que al recargar no se reenvíe el formulario.

```php
if ($resultado) {
    guardarReserva(...);
    header('Location: confirmacion.php?hashreserva=' . $hash, true, 303);
    exit;   // ¡siempre exit tras header Location!
}

http_response_code(422);   // datos del formulario no válidos
http_response_code(404);   // recurso no encontrado
```

- `header()` y `http_response_code()` tienen que ejecutarse **antes de cualquier HTML** (por eso la lógica va antes del `include` de la plantilla).

### Hash como identificador de reserva

```php
function generarHashReserva($fecha, $hora, $pista): string
{
    return hash('murmur3a', $fecha . '_' . $hora . '_' . $pista);
}
```

---

<a id="csv"></a>
## 13. Ficheros CSV con `fgetcsv`

```
Titulo,Plataforma,Precio,Stock,URL_Portada
The Witcher 3,PC,29.99,15,https://ejemplo.com/witcher.jpg
Elden Ring,PS5,69.99,8,
```

### Patrón base

```php
$stream = fopen($ruta, 'r');            // abrir en lectura
if ($stream !== false) {
    while (($fila = fgetcsv($stream)) !== false) {
        // $fila = ['The Witcher 3', 'PC', '29.99', '15', '...']
    }
    fclose($stream);                    // cerrar siempre
}
```

- `fgetcsv($stream, $longitud = 0, $separador = ',', $encierro = '"')`.
- Devuelve un **array** por línea y `false` al final del fichero.
- Todos los valores llegan como **string** → castear con `(int)`/`(float)` para operar.
- Con separador `;`: `fgetcsv($stream, 0, ';')`.

### Con cabecera → array asociativo (el más usado)

```php
function getGamesWithStock(string $ruta): array
{
    $stream = fopen($ruta, 'r');
    $juegos = [];

    if ($stream !== false) {
        $encabezado = fgetcsv($stream);              // 1ª línea = claves

        while (($fila = fgetcsv($stream)) !== false) {
            if ($fila[3] > 0) {                      // filtrar: stock > 0
                $juegos[] = array_combine($encabezado, $fila);
            }
        }
        fclose($stream);
    }
    return $juegos;
}
// $juegos[0]['Titulo'] → 'The Witcher 3'
```

### Versión robusta (restaurante): comprobaciones + campo calculado

```php
function obtenerPedidosPendientes(string $ruta): array
{
    if (!file_exists($ruta) || !is_readable($ruta)) {
        return [];
    }
    $stream = fopen($ruta, 'r');
    if ($stream === false) {
        return [];
    }

    $pendientes = [];
    $encabezado = fgetcsv($stream);

    if ($encabezado !== false) {
        $numCampos = count($encabezado);

        while (($fila = fgetcsv($stream)) !== false) {
            if (count($fila) === $numCampos) {       // evita error en array_combine
                $pedido = array_combine($encabezado, $fila);

                if (($pedido['Estado'] ?? '') === 'Pendiente') {
                    $base = (float)($pedido['Precio_Base'] ?? 0);
                    $desc = (float)($pedido['Descuento_Porcentaje'] ?? 0);
                    $pedido['Precio_Final'] = round($base - $base * $desc / 100, 2);
                    $pendientes[] = $pedido;
                }
            }
        }
    }
    fclose($stream);
    return $pendientes;
}
```

> `array_combine` lanza error si cabecera y fila tienen distinto número de elementos → comprobar `count()`.

### Indexado por un campo

```php
$cabecera = fgetcsv($fd);
while (($c = fgetcsv($fd)) !== false) {
    $coches[$c[3]] = array_combine($cabecera, $c);   // clave = matrícula
}
```

### Sin cabecera (todo el CSV a un array 2D)

```php
function getTableroFromCsv(string $ruta): array
{
    $tablero = [];
    $stream = fopen($ruta, 'r');
    if ($stream !== false) {
        while (($fila = fgetcsv($stream)) !== false) {
            $tablero[] = $fila;
        }
        fclose($stream);
    }
    return $tablero;
}
$filas = count($tablero);
$columnas = count($tablero[0]);
// $tablero[$y][$x]
```

### Buscar una fila concreta y parar

```php
while (($fila = fgetcsv($stream)) !== false) {
    if ($fila[2] === $colorBuscado) {
        $resultado = $fila;
        break;
    }
}
fclose($stream);
```

### Modos de `fopen`

| Modo | Uso |
|------|-----|
| `r` | Leer |
| `w` | Escribir desde cero (borra el contenido) |
| `a` | Añadir al final (no borra) |

---

<a id="csv-otras"></a>
## 14. Otras formas de leer CSV

```php
// file(): cada línea en un elemento del array
$lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$cabecera = str_getcsv(array_shift($lineas));   // saca la 1ª línea
foreach ($lineas as $linea) {
    $campos = str_getcsv($linea);
    // rellenar campos que faltan para poder hacer array_combine
    while (count($campos) < count($cabecera)) { $campos[] = null; }
    $datos[] = array_combine($cabecera, $campos);
}

// file_get_contents(): todo el fichero como string
$contenido = trim(file_get_contents($ruta));
$lineas = explode("\n", $contenido);
$cabecera = array_map('trim', explode(',', reset($lineas)));
```

- `str_getcsv($linea)` parsea una línea respetando comillas (mejor que `explode(',')`).

---

<a id="csv-escribir"></a>
## 15. Escribir CSV con `fputcsv`

```php
// Añadir una reserva al final del fichero
function guardarReserva(array $linea, string $ruta): void
{
    $stream = fopen($ruta, 'a');
    fputcsv($stream, $linea);       // ['Hugo', 'h@x.es', 'Tenis', '2026-10-20', '19:00', 'raquetas', 'Confirmada']
    fclose($stream);
}

// Volcar un array completo (sobrescribe)
$archivo = fopen('coches.csv', 'w');
foreach ($coches as $coche) {
    fputcsv($archivo, $coche);
}
fclose($archivo);
```

- `fputcsv` pone comillas solo cuando hace falta (p. ej. `"Pádel 1"`).

---

<a id="pdo"></a>
## 16. Base de datos con PDO

### Conexión (como en clase)

```php
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=reservapistas;charset=utf8mb4',
        'dwes',
        'abc123.',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // errores → excepciones
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // filas como array asociativo
            PDO::ATTR_EMULATE_PREPARES   => false,                  // preparadas reales
        ]
    );
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

- **DSN**: `mysql:host=...;dbname=...;charset=utf8mb4`.
- Credenciales en un array para pasarlas a las funciones:

```php
$credBBDD = [
    'dsn'      => 'mysql:host=localhost;dbname=reservapistas;charset=utf8mb4',
    'username' => 'dwes',
    'password' => 'abc123.',
];
$pdo = new PDO($credBBDD['dsn'], $credBBDD['username'], $credBBDD['password'], $opciones);
```

### SELECT sin parámetros

```php
$stmt = $pdo->query('SELECT * FROM familia');
$data = $stmt->fetchAll();        // array de filas asociativas

foreach ($data as $fila) {
    echo $fila['cod'] . ' => ' . $fila['nombre'];
}
```

### Métodos de lectura

```php
$stmt->fetchAll();     // todas las filas → [[...], [...]]  (array vacío si no hay)
$stmt->fetch();        // la siguiente fila, o false si no hay más
$stmt->fetchColumn();  // primer campo de la siguiente fila (COUNT(*))
$stmt->rowCount();     // filas afectadas (INSERT/UPDATE/DELETE)
$pdo->lastInsertId();  // id AUTO_INCREMENT del último INSERT
```

```php
while ($fila = $stmt->fetch()) { echo $fila['nombre']; }

$total = $pdo->query('SELECT COUNT(*) FROM reservas')->fetchColumn();
```

---

<a id="preparadas"></a>
## 17. Consultas preparadas

**Nunca** concatenar datos del usuario en el SQL (inyección SQL):

```php
// ❌ MAL (está así en Acceso_a_BBDD/index.php)
$sql = "SELECT cod, nombre FROM familia WHERE cod='" . $cod_fam . "'";
$pdo->query($sql);

// ✅ BIEN
$stmt = $pdo->prepare('SELECT cod, nombre FROM familia WHERE cod = ?');
$stmt->execute([$cod_fam]);
$data = $stmt->fetchAll();
```

### Posicionales `?` y con nombre `:param`

```php
$stmt = $pdo->prepare('SELECT * FROM reservas WHERE pista = ? AND date = ?');
$stmt->execute([$pista, $fecha]);   // mismo orden que los ?

$stmt = $pdo->prepare('SELECT * FROM reservas WHERE pista = :pista');
$stmt->execute([':pista' => $pista]);

$stmt = $pdo->prepare('SELECT * FROM producto WHERE PVP > :pvp');
$stmt->bindValue(':pvp', 100, PDO::PARAM_INT);
$stmt->execute();
```

### WHERE dinámico según los filtros (index de reservas)

```php
$sql = 'SELECT nombre, email, pista, date, hora, material, normas FROM reservas';
$condiciones = [];
$params = [];

if (!empty($pista)) {
    $condiciones[] = 'pista = ?';
    $params[] = $pista;
}
if (!empty($fecha)) {
    $condiciones[] = 'date = ?';
    $params[] = $fecha;
}
if ($condiciones) {
    $sql .= ' WHERE ' . implode(' AND ', $condiciones);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservas = $stmt->fetchAll();
```

### Una sola fila

```php
$stmt = $pdo->prepare('SELECT * FROM reservas WHERE id = ?');
$stmt->execute([$id]);
$reserva = $stmt->fetch();      // array o false

if (!$reserva) {
    http_response_code(404);
}
```

---

<a id="insert"></a>
## 18. INSERT / UPDATE / DELETE

```php
// INSERT
$stmt = $pdo->prepare(
    'INSERT INTO reservas (nombre, email, pista, date, hora, material, normas)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$stmt->execute([$nombre, $email, $pista, $fecha, $hora, $material, 'Confirmada']);
$id = $pdo->lastInsertId();

// UPDATE
$stmt = $pdo->prepare('UPDATE reservas SET hora = ? WHERE id = ?');
$stmt->execute(['18:00', $id]);
echo $stmt->rowCount();         // filas modificadas

// DELETE
$stmt = $pdo->prepare('DELETE FROM reservas WHERE id = ?');
$stmt->execute([$id]);
```

### Comprobar si ya existe (mejor que traer toda la tabla y recorrerla)

```php
$stmt = $pdo->prepare('SELECT COUNT(*) FROM reservas WHERE pista = ? AND date = ? AND hora = ?');
$stmt->execute([$pista, $fecha, $hora]);
$existe = $stmt->fetchColumn() > 0;
```

- La tabla `reservas` tiene `UNIQUE (pista, date, hora)`: un INSERT repetido lanza `PDOException` (código `23000`).
- En MySQL `TIME` se guarda como `16:00:00` → `substr($hora, 0, 5)` para obtener `16:00`.

---

<a id="patron-pdo"></a>
## 19. Patrón completo: reservas con PDO

```php
// functions/functions_index.php
function conectar(): PDO
{
    return new PDO(
        'mysql:host=localhost;dbname=reservapistas;charset=utf8mb4',
        'dwes',
        'abc123.',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
}

function getInputs(): array
{
    return [
        filter_input(INPUT_GET, 'pista'),
        filter_input(INPUT_GET, 'fecha'),
    ];
}

function leerReservas(array $inputs): array
{
    try {
        $pdo = conectar();
        $sql = 'SELECT nombre, email, pista, date, hora, material, normas FROM reservas';
        $params = [];
        // ... WHERE dinámico (apartado 17)
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo '<h1>Error con la BBDD</h1>';
        return [];
    }
}
```

```php
// index.php
<?php
include __DIR__ . '/functions/functions_index.php';

$inputs = getInputs();
$tabla_reservas = leerReservas($inputs);

include __DIR__ . '/templates/index.tpl.php';
```

### Pasar de CSV a BBDD (lo que cambia)

| CSV | PDO |
|-----|-----|
| `fopen($ruta, 'r')` | `new PDO($dsn, $user, $pass, $opts)` |
| `while (fgetcsv(...))` | `$stmt->fetchAll()` |
| Filtrar con `array_filter` | `WHERE ... = ?` en el SQL |
| `fputcsv($stream, $linea)` en modo `a` | `INSERT ... VALUES (?, ...)` |
| Recorrer para ver si existe | `SELECT COUNT(*) ... WHERE` |
| `$fila[2]` (índice numérico) | `$fila['pista']` (FETCH_ASSOC) |

---

<a id="fechas"></a>
## 20. Fechas con `DateTime`

```php
$fecha = DateTime::createFromFormat('Y-m-d', $fechaStr);  // false si no es válida
$hoy = new DateTime();
$hoy->setTime(0, 0, 0);
$limite = (clone $hoy)->modify('+14 days');

if (!$fecha || $fecha < $hoy || $fecha > $limite) {
    $errores[] = 'La fecha debe ser entre hoy y dentro de 14 días.';
}

echo $hoy->format('d/m/Y');   // 28/10/2026
date('Y-m-d');                // fecha actual como string
```

- El `<input type="date">` envía siempre `Y-m-d`.
- `clone` evita modificar `$hoy` al hacer `modify()`.

---

<a id="errores"></a>
## 21. Errores típicos

- **Variable sin `<?= ?>` en atributos**: `<img src="$imgURL">` imprime el texto literal → `<img src="<?= e($imgURL) ?>">`.
- **`header()` después de HTML** → "headers already sent". Lógica antes del `include` de la plantilla.
- **Olvidar `exit`** tras `header('Location: ...')`.
- **`array_combine` con distinto nº de elementos** → comprobar `count()`.
- **Comparar con `==`** cuando importa el tipo → usar `===`.
- **Concatenar input en SQL** → consultas preparadas.
- **No cerrar el fichero** → `fclose($stream)` (también antes de un `return` dentro del bucle).
- **Función declarada dos veces** al incluir varios ficheros → `require_once`.
- **Valores del CSV son strings** → `(float)` / `(int)` antes de operar.
- **No escapar** datos al imprimir → `e()`.

---

<a id="express"></a>
## 22. Chuleta express

| Tarea | Código |
|-------|--------|
| Ver errores | `ini_set('display_errors', 1); error_reporting(E_ALL);` |
| Debug | `echo '<pre>' . print_r($x, true) . '</pre>';` |
| Ruta segura | `__DIR__ . '/data/x.csv'` |
| Incluir | `require_once __DIR__ . '/functions/f.php';` |
| Escapar | `htmlspecialchars($s, ENT_QUOTES, 'UTF-8')` |
| Leer GET | `filter_input(INPUT_GET, 'campo')` |
| Validar int | `filter_input(INPUT_GET, 'x', FILTER_VALIDATE_INT)` |
| Validar email | `filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)` |
| ¿Es POST? | `$_SERVER['REQUEST_METHOD'] === 'POST'` |
| Abrir CSV | `$s = fopen($ruta, 'r');` |
| Leer línea | `while (($f = fgetcsv($s)) !== false) {}` |
| Cabecera + fila | `array_combine($cabecera, $fila)` |
| Añadir línea | `fputcsv(fopen($ruta, 'a'), $linea)` |
| Conectar BBDD | `new PDO($dsn, $user, $pass, $opts)` |
| SELECT con filtro | `$st = $pdo->prepare($sql); $st->execute([$v]);` |
| Todas las filas | `$st->fetchAll()` |
| Una fila | `$st->fetch()` |
| Contar | `$st->fetchColumn()` |
| Redirigir (PRG) | `header('Location: x.php', true, 303); exit;` |
| Código HTTP | `http_response_code(404);` |
