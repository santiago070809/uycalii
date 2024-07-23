<?php
// confirmacion_payu.php

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uycali";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el código de referencia del pago
$referenceCode = $_GET['referenceCode'] ?? '';

if ($referenceCode) {
    // Preparar la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT * FROM pedidos_temp WHERE reference_code = ?");
    $stmt->bind_param("s", $referenceCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pedido = $result->fetch_assoc();

        // Preparar la inserción en la tabla por_enviar
        $stmt = $conn->prepare("INSERT INTO por_enviar (reference_code, nombre_cliente, direccion, telefono, email, items, monto_total)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", 
            $pedido['reference_code'],
            $pedido['nombre_cliente'],
            $pedido['direccion'],
            $pedido['telefono'],
            $pedido['email'],
            $pedido['items'],
            $pedido['monto_total']
        );

        if ($stmt->execute()) {
            // Preparar la eliminación del pedido de la tabla pedido_temp
            $stmt = $conn->prepare("DELETE FROM pedidos_temp WHERE reference_code = ?");
            $stmt->bind_param("s", $referenceCode);
            $stmt->execute();

            $message = 'Pedido procesado y movido a por_enviar.';
        } else {
            $message = 'Error al procesar el pedido: ' . $stmt->error;
        }
    } else {
        $message = 'Pedido no encontrado.';
    }

    $stmt->close();
} else {
    $message = 'Código de referencia no proporcionado.';
}

$conn->close();
?>

<!-- Agregar un botón de regreso al sitio -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pago</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004494;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gracias por su compra</h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="index.html" class="btn btn-primary">Regresar al sitio</a>
    </div>
</body>
</html>
