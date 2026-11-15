<?php

include_once(__DIR__ . '/db.php');

function validarUsuario(string $usuario): array
{
    $usuario = trim($usuario);
    $resultado = [
        'isValid' => false,
        'mensaje' => '',
        'valor'   => $usuario
    ];

    if (strlen($usuario) > 100) {
        $resultado['mensaje'] = 'La longitud no puede ser mayor a 100';
    } elseif (strlen($usuario) < 1) {
        $resultado['mensaje'] = 'La longitud no puede ser menor a 1';
    } elseif (strpbrk($usuario, '@-,<>/&')) {
        $resultado['mensaje'] = 'El usuario no puede contener ninguno de: @-,<>/&';
    } else {
        $resultado['isValid'] = true;
    }

    return $resultado;
}

function validarEmail(string $email): array
{
    $email = trim($email);
    $resultado = [
        'isValid' => false,
        'mensaje' => '',
        'valor'   => $email
    ];

    if (strlen($email) > 100) {
        $resultado['mensaje'] = 'La longitud no puede ser mayor a 100';
    } elseif (strlen($email) < 1) {
        $resultado['mensaje'] = 'La longitud no puede ser menor a 1';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $resultado['mensaje'] = 'El email no es correcto';
    } elseif (strpbrk($email, '<>$~#')) {
        $resultado['mensaje'] = 'El email no puede contener ninguno de: <>$~#';
    } else {
        $resultado['isValid'] = true;
    }

    return $resultado;
}

function validarPassword(string $password1, string $password2): array
{
    $resultado = [
        'isValid' => false,
        'mensaje' => '',
        'valor'   => $password1
    ];

    if (strlen($password1) > 255) {
        $resultado['mensaje'] = 'La longitud no puede ser mayor a 255';
    } elseif (strlen($password1) < 8) {
        $resultado['mensaje'] = 'La contraseña debe tener al menos 8 caracteres';
    } elseif ($password1 !== $password2) {
        $resultado['mensaje'] = 'Las contraseñas no coinciden';
    } else {
        $resultado['isValid'] = true;
    }

    return $resultado;
}

function hashearPassword(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

function registrarUsuario(string $email, string $nombre, string $contrasena): null|bool
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO usuarios (email, nombre, contrasena) VALUES (:email, :nombre, :contrasena)');
        $stmt->execute([
            'email'      => $email,
            'nombre'     => $nombre,
            'contrasena' => $contrasena
        ]);

        return $stmt->rowCount() > 0;
    } catch (PDOException) {
        return null;
    }
}

function validarInicioSesion(string $email, string $contrasena): null|false|array
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, nombre, contrasena FROM usuarios WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return [
                'id'     => $usuario['id'],
                'nombre' => $usuario['nombre']
            ];
        }

        return false;
    } catch (PDOException) {
        return null;
    }
}

function getProducts(): null|false|array
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, nombre, precio, stock FROM productos');
        $stmt->execute();
        $productos = $stmt->fetchAll();

        return !empty($productos) ? $productos : false;
    } catch (PDOException) {
        return null;
    }
}

function validarProducto(int $id_producto): null|bool
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if ($id_producto < 1) {
        return false;
    }

    try {
        $stmt = $pdo->prepare('SELECT id FROM productos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id_producto]);
        $producto = $stmt->fetch();

        return $producto !== false;
    } catch (PDOException) {
        return null;
    }
}

function getStock(int $id_producto): null|false|int
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if ($id_producto < 1) {
        return false;
    }

    try {
        $stmt = $pdo->prepare('SELECT stock FROM productos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id_producto]);
        $stock = $stmt->fetch(PDO::FETCH_COLUMN);

        return $stock !== false ? (int)$stock : false;
    } catch (PDOException) {
        return null;
    }
}

function getProductsFromIds(array $ids_productos): null|array
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if (empty($ids_productos)) {
        return [];
    }

    try {
        $placeholders = implode(',', array_fill(0, count($ids_productos), '?'));
        $stmt = $pdo->prepare("SELECT id, nombre, precio, stock FROM productos WHERE id IN ($placeholders)");
        $stmt->execute(array_values($ids_productos));

        return $stmt->fetchAll();
    } catch (PDOException) {
        return null;
    }
}

function ejecutarCompra(array $carrito): null|bool
{
    $pdo = conexion();

    if ($pdo === null || empty($carrito)) {
        return null;
    }

    try {
        $pdo->beginTransaction();

        // 1. Calculamos el total real en servidor y verificamos stock antes de insertar nada
        $totalReal = 0.0;
        $detallesCompra = [];

        $stmtProd = $pdo->prepare('SELECT precio, stock FROM productos WHERE id = :id LIMIT 1');

        foreach ($carrito as $id => $cantidad) {
            $id = (int)$id;
            $cantidad = (int)$cantidad;

            $stmtProd->execute(['id' => $id]);
            $prod = $stmtProd->fetch();

            if (!$prod || $prod['stock'] < $cantidad) {
                $pdo->rollBack();
                $_SESSION['flash_messages']['sinstock'] = 'No hay stock suficiente para completar el pedido';
                header('Location: carrito.php', true, 303);
                exit;
            }

            $totalReal += (float)$prod['precio'] * $cantidad;
            $detallesCompra[$id] = [
                'cantidad' => $cantidad,
                'precio'   => (float)$prod['precio']
            ];
        }

        // 2. Insertamos cabecera del pedido con el $totalReal calculado en servidor
        $stmt = $pdo->prepare('INSERT INTO pedidos (usuario_id, total) VALUES (:usuario_id, :total)');
        $stmt->execute([
            'usuario_id' => $_SESSION['id'],
            'total'      => $totalReal
        ]);

        $pedido_id = (int)$pdo->lastInsertId();

        // 3. Insertamos líneas y descontamos stock
        $stmtLinea = $pdo->prepare(
            'INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio_unitario) 
             VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)'
        );
        $stmtStock = $pdo->prepare('UPDATE productos SET stock = stock - :cant WHERE id = :id');

        foreach ($detallesCompra as $id => $datos) {
            $stmtLinea->execute([
                'pedido_id'       => $pedido_id,
                'producto_id'     => $id,
                'cantidad'        => $datos['cantidad'],
                'precio_unitario' => $datos['precio']
            ]);

            $stmtStock->execute([
                'cant' => $datos['cantidad'],
                'id'   => $id
            ]);
        }

        $pdo->commit();
        unset($_SESSION['carrito']);
        $_SESSION['flash_messages']['confirmacion'] = 'Compra realizada correctamente';

        header('Location: bienvenida.php', true, 303);
        exit;
    } catch (PDOException) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return null;
    }
}

function getPedidos(int $id): null|false|array
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    try {
        $stmt = $pdo->prepare(
            "SELECT id, total, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha_espanol 
             FROM pedidos 
             WHERE usuario_id = :usuario_id 
             ORDER BY fecha DESC"
        );
        $stmt->execute(['usuario_id' => $id]);
        $pedidos = $stmt->fetchAll();

        return !empty($pedidos) ? $pedidos : false;
    } catch (PDOException) {
        return null;
    }
}
