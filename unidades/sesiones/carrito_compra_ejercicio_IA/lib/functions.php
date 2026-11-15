<?php

include_once(__DIR__ . '/db.php');

// DEBUG
function dump(mixed $var)
{
    echo '<pre>' . print_r($var, true) . '</pre>';
}

function validarUsuario(string $usuario): array
{
    $resultado = [
        'isValid' => false,
        'mensaje' => '',
        'valor' => $usuario
    ];

    if (isset($usuario)) {
        $usuario = trim($usuario);
    } else {
        return $resultado;
    }


    if (strlen($usuario) > 100) {
        $resultado['mensaje'] = 'La longitud no puede ser mayor a 100';
    } else if (strlen($usuario) < 1) {
        $resultado['mensaje'] = 'La longitud no puede ser menor a 1';
    } else if (!filter_var($usuario, FILTER_UNSAFE_RAW)) {
        $resultado['mensaje'] = 'El usuario no es correcto';
    } else if (strpbrk($usuario, '@-,<>/&')) {
        $resultado['mensaje'] = 'El usuario no puede contener ninguno de: @-,<>/&';
    } else {
        $resultado['isValid'] = true;
    }

    return $resultado;
}

function validarEmail(string $email): array
{
    $resultado = [
        'isValid' => false,
        'mensaje' => '',
        'valor' => $email
    ];


    if (isset($email)) {
        $email = trim($email);
    } else {
        return $resultado;
    }


    if (strlen($email) > 100) {
        $resultado['mensaje'] = 'La longitud no puede ser mayor a 100';
    } else if (strlen($email) < 1) {
        $resultado['mensaje'] = 'La longitud no puede ser menor a 1';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $resultado['mensaje'] = 'El email no es correcto';
    } else if (strpbrk($email, '<>$~#')) {
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
        'valor' => $password1
    ];


    if (!isset($password1) || !isset($password2)) {
        return $resultado;
    }


    if (strlen($password1) > 255) {
        $resultado['mensaje'] = 'La longitud no puede ser mayor a 255';
    } else if (strlen($password1) < 1) {
        $resultado['mensaje'] = 'La longitud no puede ser menor a 1';
    } else if (!filter_var($password1, FILTER_UNSAFE_RAW)) {
        $resultado['mensaje'] = 'La contraseña no es correcta';
    } else if ($password1 !== $password2) {
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

function coincidenPasswords(string $password1, string $password2): bool
{
    return $password1 === $password2 ? true : false;
}

function registrarUsuario(string $email, string $nombre, string $contrasena): null|bool
{
    $pdo = conexion();

    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare('INSERT INTO usuarios (email, nombre, contrasena) VALUES (:email, :nombre, :contrasena)');
            $stmt->execute(['email' => $email, 'nombre' => $nombre, 'contrasena' => $contrasena]);

            if ($stmt->rowCount() > 0) {
                return true;
            }

            return false;
        } catch (PDOException) {
            return null;
        }
    }

    return null;
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
                'id' => $usuario['id'],
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
        $stmt = $pdo->prepare('SELECT * FROM productos');
        $stmt->execute();
        $productos = $stmt->fetchAll();

        if (is_array($productos)) {
            return $productos;
        }

        return false;
    } catch (PDOException) {
        return null;
    }
}

function validarProducto(int $id_producto)
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if ($id_producto < 1) {
        return false;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id_producto]);
        $producto = $stmt->fetch();

        if ($producto) {
            return true;
        }

        return false;
    } catch (PDOException) {
        return null;
    }
}

function getStock(int $id_producto)
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

        if (isset($stock)) {
            return $stock;
        }

        return false;
    } catch (PDOException) {
        return null;
    }
}

function getPrice(int $id_producto)
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if ($id_producto < 1) {
        return false;
    }

    try {
        $stmt = $pdo->prepare('SELECT precio FROM productos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id_producto]);
        $precio = $stmt->fetch(PDO::FETCH_COLUMN);

        if (isset($precio)) {
            return $precio;
        }

        return false;
    } catch (PDOException) {
        return null;
    }
}

function getProductsFromIds(array $ids_productos): null|false|array
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if ($ids_productos === null) {
        return false;
    }

    try {
        $productos = [];

        foreach ($ids_productos as $id_producto) {
            $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = :id');
            $stmt->execute(['id' => $id_producto]);
            $producto = $stmt->fetch();

            if (isset($producto)) {
                $productos[] = $producto;
            }
        }

        return $productos;
    } catch (PDOException) {
        return null;
    }
}

function ejecutarCompra(array $carrito, float $total)
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if ($carrito === null) {
        return false;
    }

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare('INSERT INTO pedidos (usuario_id, total) VALUES (:usuario_id, :total)');
        $stmt->execute([
            'usuario_id' => $_SESSION['id'],
            'total' => $total
        ]);

        $pedido_id = (int)$pdo->lastInsertId();

        foreach ($carrito as $id => $cantidad) {

            $stock = getStock($id);
            if ($stock < $cantidad) {
                $pdo->rollBack();
                $_SESSION['flash_messages']['sinstock'] = 'No hay stock suficiente del producto';
                header('Location: carrito.php', true, 302);
                exit;
            } else {
                $precio = getPrice($id);

                $stmt = $pdo->prepare('INSERT INTO lineas_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)');
                $stmt->execute([
                    'pedido_id'       => $pedido_id,
                    'producto_id'     => $id,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio
                ]);

                $stmt = $pdo->prepare('UPDATE productos SET stock = stock - :cant WHERE id = :id');
                $stmt->execute([
                    'cant' => $cantidad,
                    'id' => $id
                ]);
            }
        }

        $pdo->commit();
        unset($_SESSION['carrito']);
        $_SESSION['flash_messages']['confirmacion'] = 'Compra realizada';

        header('Location: bienvenida.php', true, 302);
        exit;
    } catch (PDOException) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return null;
    }
}

function getPedidos(int $id)
{
    $pdo = conexion();

    if ($pdo === null) {
        return null;
    }

    if ($id === null) {
        return false;
    }

    try {
        $stmt = $pdo->prepare("SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y %H:%i') AS fecha_espanol FROM pedidos WHERE usuario_id = :usuario_id ORDER BY fecha");
        $stmt->execute(['usuario_id' => $id]);
        $pedidos = $stmt->fetchAll();

        if (!empty($pedidos)) {
            return $pedidos;
        }


        return false;
    } catch (PDOException) {
        return null;
    }
}
