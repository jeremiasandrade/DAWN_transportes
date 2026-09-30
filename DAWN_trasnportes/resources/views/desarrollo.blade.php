


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desarrollo</title>
</head>
<body>
    <h1>Desarrollo</h1>
    <p>Esta es la vista de desarrollo donde puedes acceder a todas las vistas del sistema.</p>
    <ul>
        <li><a href="{{ url('/clientes') }}">Clientes</a></li>
        <li><a href="{{ url('/vehiculos') }}">Vehículos</a></li>
        <li><a href="{{ url('/rutas') }}">Rutas</a></li>

</body>
</html>