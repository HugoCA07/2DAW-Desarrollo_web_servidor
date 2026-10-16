<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUSCAR PISTAS</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <!-- FORMULARIO PARA EL GET -->
    <form action="" method="get">
        <label for="pista">Pista:</label>
        <select id="pista" name="pista">
            <option value="" disabled selected>Selecciona una pista...</option>
            <option value="Pádel 1">Pádel 1</option>
            <option value="Pádel 2">Pádel 2</option>
            <option value="Tenis">Tenis</option>
            <option value="Baloncesto">Baloncesto</option>
        </select>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha">

        <button type="submit">Buscar</button>
    </form>


</body>

</html>