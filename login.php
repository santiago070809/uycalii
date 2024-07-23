<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        form { max-width: 300px; margin: 0 auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 10px; margin-top: 5px; }
        button { width: 100%; background-color: #4CAF50; color: white; border: none; cursor: pointer; padding: 10px; margin-top: 10px; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <form action="login_process.php" method="POST">
        <h2>Iniciar Sesión</h2>
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Iniciar Sesión</button>
        <button type="button" onclick="window.location.href='index.html'">Inicio</button>
    </form>
</body>
</html>
