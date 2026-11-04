# Cheatsheet PHP — DWES (2º DAW)

> Lo esencial para cualquier ejercicio. Código solo en lo más difícil: leer CSV, conexión PDO y WHERE dinámico.

## Índice

1. [Estructura del proyecto](#estructura)
2. [Plantillas `.tpl.php`](#plantillas)
3. [Escapar la salida con `e()`](#escapar)
4. [Formularios y `filter_input`](#formularios)
5. [Validación y persistencia](#validacion)
6. [PRG y códigos HTTP](#http)
7. [Leer CSV con `fgetcsv`](#csv)
8. [Escribir CSV con `fputcsv`](#csv-escribir)
9. [Conexión PDO](#pdo)
10. [Consultas preparadas](#preparadas)
11. [Métodos de PDO](#metodos)
12. [De CSV a BBDD](#csv-bbdd)
13. [Fechas con `DateTime`](#fechas)
14. [Errores típicos](#errores)
15. [Chuleta express](#express)

---

<a id="estructura"></a>
## 1. Estructura del proyecto

| Fichero | Qué hace |
|---------|----------|
| `index.php` | Controlador: llama a las funciones, guarda el resultado en variables e incluye la plantilla |
| `functions/*.php` | Lógica: leer CSV o BBDD, validar, guardar |
| `templates/*.tpl.php` | Vista: solo HTML y pintar variables |
| `data/` | `.csv` o `.sql` |

- Rutas siempre con `__DIR__ . '/...'`.
- `require_once` para funciones (evita "function already declared").
- **La lógica va antes de incluir la plantilla.** `header()` y `http_response_code()` se ejecutan antes de enviar cualquier HTML.
- Las funciones **devuelven** datos (arrays); no imprimen HTML.

---

<a id="plantillas"></a>
## 2. Plantillas `.tpl.php`

- Sintaxis alternativa: `if (): … else: … endif;` y `foreach (): … endforeach;`.
- Imprimir: `<?= e($x) ?>` (equivale a `<?php echo e($x); ?>`).
- Siempre hay que contemplar el caso vacío (`if (!empty($datos))` … `else` "No hay resultados").
- Tabla de filas de CSV o BBDD: un `foreach` de filas con un `foreach` de celdas dentro.
- Las variables dentro de atributos también necesitan `<?= ?>`: `src="<?= e($url) ?>"`.

---

<a id="escapar"></a>
## 3. Escapar la salida con `e()`

`e()` es un envoltorio de `htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8')`.

Todo lo que venga del usuario, del CSV o de la BBDD se imprime con `e()`, también dentro de `value=""` y `src=""`. Así se evita el **XSS**.

---

<a id="formularios"></a>
## 4. Formularios y `filter_input`

| Método | Cuándo |
|--------|--------|
| `GET` | Filtros y búsquedas (los datos van en la URL) |
| `POST` | Crear o enviar datos |

`filter_input(INPUT_GET|INPUT_POST, 'campo', FILTRO)`:

| Filtro | Uso |
|--------|-----|
| *(ninguno)* / `FILTER_UNSAFE_RAW` | Texto tal cual |
| `FILTER_VALIDATE_INT` | Enteros (ids, coordenadas) |
| `FILTER_VALIDATE_EMAIL` | Emails |
| `FILTER_CALLBACK` + `FILTER_REQUIRE_ARRAY` | Checkboxes `name="x[]"`, validando cada valor con una función |

| Devuelve | Significa |
|----------|-----------|
| `null` | El campo no venía |
| `false` | Venía, pero no pasa el filtro |
| valor | Correcto |

- Hay que comprobar los dos casos: `$x === null || $x === false`.
- ¿Se ha enviado el formulario?: `$_SERVER['REQUEST_METHOD'] === 'POST'`.
- Un checkbox sin marcar **no se envía** (`filter_input` devuelve `null`).
- Los checkboxes `name="x[]"` llegan como array.

---

<a id="validacion"></a>
## 5. Validación y persistencia

Patrón: la función de validación devuelve `['errores' => [...], 'valores' => [...]]`.
- `$errores[]` → un mensaje por cada fallo.
- `$valores[]` → los datos correctos, que sirven para guardar y para la persistencia.
- Solo se valida si el método es POST.

Validaciones típicas:
- **Vacío**: `trim()` y comparar con `''`.
- **Longitud**: `strlen()` entre un mínimo y un máximo.
- **Lista blanca** (select, radio): `in_array($valor, getOpciones())`. Las opciones salen de un array, nunca a mano.
- **Email / entero**: `FILTER_VALIDATE_EMAIL` / `FILTER_VALIDATE_INT`.
- **Rango numérico**: `$n < $min || $n > $max`.
- **Checkbox obligatorio**: `filter_input` no debe ser `null`.
- **Checkboxes múltiples**: `array_filter()` y `implode(', ', …)` para guardarlos.
- **Fecha**: ver [`DateTime`](#fechas).

Persistencia (que no se borre lo escrito cuando hay error):

| Campo | En la plantilla |
|-------|-----------------|
| `input` | `value="<?= e($previos['campo'] ?? '') ?>"` |
| `select` | `selected` si coincide con el valor previo |
| `radio` | `checked` si coincide |
| `checkbox[]` | `checked` si `in_array($op, $previos['campo'] ?? [])` |

---

<a id="http"></a>
## 6. PRG y códigos HTTP

**PRG (Post/Redirect/Get):** después de guardar un POST correcto se redirige, para que al recargar no se reenvíe el formulario.

`header('Location: exito.php?id=' . $id, true, 303); exit;`

| Código | Cuándo |
|--------|--------|
| `303` | Redirección tras un POST correcto |
| `422` | Formulario con errores de validación |
| `404` | Recurso no encontrado (`fetch()` devolvió `false`) |

- Los códigos se ponen con `http_response_code(422);`.
- Después de un `header('Location')` siempre va `exit`.

---

<a id="csv"></a>
## 7. Leer CSV con `fgetcsv`

```php
$stream = fopen($ruta, 'r');
$cabecera = fgetcsv($stream);
while (($fila = fgetcsv($stream)) !== false) {
    $datos[] = array_combine($cabecera, $fila);
}
fclose($stream);
```

- `fgetcsv` devuelve **un array por línea** y `false` al llegar al final. Por eso la comparación es `!== false`.
- La cabecera se lee aparte y `array_combine($cabecera, $fila)` da arrays asociativos.
- `array_combine` **falla** si cabecera y fila no tienen el mismo número de campos: comprobar `count($fila) === count($cabecera)`.
- Todos los valores llegan como **string**: `(int)` / `(float)` antes de operar.
- Antes de abrir: `file_exists()` y `is_readable()`, y comprobar que `fopen` no devuelva `false`.
- Para indexar por un campo: `$datos[$fila[3]] = …`.
- CSV sin cabecera: `$tabla[] = $fila` → array 2D `$tabla[$y][$x]`.
- Separador `;`: `fgetcsv($stream, 0, ';')`.
- `fclose()` también antes de un `return` o `break` dentro del bucle.

| Modo `fopen` | Uso |
|--------------|-----|
| `r` | Leer |
| `w` | Escribir desde cero (borra el contenido) |
| `a` | Añadir al final |

---

<a id="csv-escribir"></a>
## 8. Escribir CSV con `fputcsv`

- `fputcsv($stream, $arrayFila)` escribe una línea y pone comillas solo donde hacen falta.
- Para añadir se abre con `'a'`. Con `'w'` se borraría todo el fichero.
- Para comprobar duplicados antes de guardar hay que recorrer el CSV comparando el campo clave.

---

<a id="pdo"></a>
## 9. Conexión PDO

```php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=NOMBRE_BD;charset=utf8mb4', 'usuario', 'clave', [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

| Opción | Para qué |
|--------|----------|
| `ERRMODE_EXCEPTION` | Los errores SQL lanzan `PDOException` |
| `FETCH_ASSOC` | Las filas llegan como `$fila['columna']` |
| `EMULATE_PREPARES => false` | Las consultas preparadas son reales, en el servidor |

- Todo el acceso a BBDD va dentro de `try/catch (PDOException $e)`.
- Las credenciales pueden ir en un array (`dsn`, `username`, `password`) que se pasa a las funciones.

---

<a id="preparadas"></a>
## 10. Consultas preparadas

**Regla de oro: nunca se concatena un dato del usuario en el SQL** (inyección SQL).

- Flujo: `prepare($sql)` → `execute([$valores])` → `fetchAll()` / `fetch()`.
- Parámetros posicionales `?` (en orden) o con nombre `:col` (`[':col' => $v]`).
- `query()` solo para SQL fijo, sin datos externos.
- `INSERT INTO tabla (c1, c2) VALUES (?, ?)` · `UPDATE tabla SET c1 = ? WHERE id = ?` · `DELETE FROM tabla WHERE id = ?`.

**WHERE dinámico** (filtros opcionales):

```php
$sql = 'SELECT * FROM tabla';
$cond = [];
$params = [];
if (!empty($a)) { $cond[] = 'columna_a = ?'; $params[] = $a; }
if (!empty($b)) { $cond[] = 'columna_b = ?'; $params[] = $b; }
if ($cond) { $sql .= ' WHERE ' . implode(' AND ', $cond); }

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
```

---

<a id="metodos"></a>
## 11. Métodos de PDO

| Método | Devuelve |
|--------|----------|
| `fetchAll()` | Todas las filas (array vacío si no hay ninguna) |
| `fetch()` | Una fila, o `false` si no hay → útil para el 404 |
| `fetchColumn()` | Un solo valor, para `SELECT COUNT(*)` |
| `rowCount()` | Filas afectadas por INSERT, UPDATE o DELETE |
| `$pdo->lastInsertId()` | Id del último INSERT |

- **¿Ya existe el registro?** → `SELECT COUNT(*) FROM tabla WHERE … = ?` y `fetchColumn() > 0`. No hace falta traer la tabla entera y recorrerla.
- Un `UNIQUE` en la tabla hace que un INSERT duplicado lance `PDOException`.
- `TIME` llega como `16:00:00`; con `substr($hora, 0, 5)` se queda en `16:00`.

---

<a id="csv-bbdd"></a>
## 12. De CSV a BBDD

| CSV | PDO |
|-----|-----|
| `fopen` + `fgetcsv` | `prepare` + `execute` + `fetchAll` |
| Filtrar con `array_filter` | `WHERE … = ?` en el SQL |
| `fputcsv` en modo `a` | `INSERT … VALUES (?, …)` |
| Recorrer para ver si existe | `SELECT COUNT(*) … WHERE` |
| `$fila[2]` (índice) | `$fila['columna']` (FETCH_ASSOC) |
| Comprobar `fopen !== false` | `try/catch PDOException` |

La estructura (index, functions, templates), la validación y el PRG **no cambian**; solo cambian las funciones que leen y guardan.

---

<a id="fechas"></a>
## 13. Fechas con `DateTime`

- `DateTime::createFromFormat('Y-m-d', $texto)` devuelve `false` si la fecha no es válida. `<input type="date">` siempre envía `Y-m-d`.
- Hoy a las 00:00: `(new DateTime())->setTime(0, 0, 0)`.
- Límite de N días: `(clone $hoy)->modify('+14 days')`. Se usa `clone` para no modificar `$hoy`.
- Los objetos `DateTime` se comparan directamente con `<` y `>`.

---

<a id="errores"></a>
## 14. Errores típicos

- `header()` después de HTML → *headers already sent*.
- Olvidar `exit` tras `header('Location')`.
- Concatenar el input en el SQL en vez de usar `prepare`.
- `<img src="$url">` sin `<?= ?>`: imprime el texto literal.
- `array_combine` con distinto número de campos.
- Usar `==` donde importa el tipo → usar `===`.
- No escapar con `e()` al imprimir.
- No cerrar el fichero con `fclose()`.
- Operar con valores del CSV sin castearlos.
- Abrir con `'w'` cuando se quería añadir (`'a'`).

---

<a id="express"></a>
## 15. Chuleta express

| Tarea | Código |
|-------|--------|
| Ver errores | `ini_set('display_errors', 1); error_reporting(E_ALL);` |
| Debug | `echo '<pre>' . print_r($x, true) . '</pre>';` |
| ¿Es POST? | `$_SERVER['REQUEST_METHOD'] === 'POST'` |
| Leer campo | `filter_input(INPUT_GET, 'campo')` |
| Validar int / email | `FILTER_VALIDATE_INT` / `FILTER_VALIDATE_EMAIL` |
| Escapar | `e($x)` |
| Leer línea CSV | `while (($f = fgetcsv($s)) !== false)` |
| Cabecera + fila | `array_combine($cab, $fila)` |
| Añadir al CSV | `fputcsv(fopen($ruta, 'a'), $linea)` |
| Consulta segura | `$st = $pdo->prepare($sql); $st->execute([$v]);` |
| Todas / una / contar | `fetchAll()` / `fetch()` / `fetchColumn()` |
| PRG | `header('Location: x.php', true, 303); exit;` |
| Error validación | `http_response_code(422);` |
| No encontrado | `http_response_code(404);` |