<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESERVA UNA PISTA</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css">
</head>

<body>
    <form method="POST">
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div>
            <label for="pista">Pista:</label>
            <select id="pista" name="pista" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="Pádel 1">Pádel 1</option>
                <option value="Pádel 2">Pádel 2</option>
                <option value="Tenis">Tenis</option>
                <option value="Baloncesto">Baloncesto</option>
            </select>
        </div>

        <div>
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required>
        </div>

        <div>
            <label>Hora:</label>
            <input type="radio" id="hora_16" name="hora" value="16:00" required>
            <label for="hora_16">16:00</label>

            <input type="radio" id="hora_17" name="hora" value="17:00">
            <label for="hora_17">17:00</label>

            <input type="radio" id="hora_18" name="hora" value="18:00">
            <label for="hora_18">18:00</label>

            <input type="radio" id="hora_19" name="hora" value="19:00">
            <label for="hora_19">19:00</label>

            <input type="radio" id="hora_20" name="hora" value="20:00">
            <label for="hora_20">20:00</label>

            <input type="radio" id="hora_21" name="hora" value="21:00">
            <label for="hora_21">21:00</label>

        </div>

        <div>
            <label>Material:</label>
            <input type="checkbox" id="mat_raquetas" name="material[]" value="raquetas">
            <label for="mat_raquetas">Raquetas</label>

            <input type="checkbox" id="mat_balon" name="material[]" value="balon">
            <label for="mat_balon">Balón</label>

            <input type="checkbox" id="mat_petos" name="material[]" value="petos">
            <label for="mat_petos">Petos</label>
        </div>

        <div>
            <input type="checkbox" id="normas" name="normas" value="aceptado" required>
            <label for="normas">Acepto las normas</label>
        </div>

        <div>
            <button type="submit">Enviar</button>
        </div>
    </form>

</body>

</html>