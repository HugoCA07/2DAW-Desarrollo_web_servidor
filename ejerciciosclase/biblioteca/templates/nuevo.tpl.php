<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nuevo libro</title>
</head>
<body>
    <form method="POST" action="">
        <label>Nuevo libro</label>
        <label>
            <input type="text" name="isbn" placeholder="ISBN">
            <input type="text" name="titulo" placeholder="Título del libro">
            <input type="text" name="autor" placeholder="Autor del libro">
            <input type="number" name="anio" placeholder="Año del libro">
            <input type="text" name="genero" placeholder="Género del libro">
            <button type="submit">Añadir</button>
        </label>
    </form>
</body>
</html>