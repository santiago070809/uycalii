<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$servername = "localhost"; // Cambia esto si el servidor de base de datos no está en el mismo servidor
$username = "root"; // Reemplaza con tu nombre de usuario
$password = ""; // Reemplaza con tu contraseña
$dbname = "uycali"; // Cambia esto por el nombre de tu base de datos

// Obtener los datos enviados en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

// Verificar si los datos fueron recibidos
if (isset($data['referenceCode']) && isset($data['nombre']) && isset($data['direccion']) && isset($data['telefono']) && isset($data['email']) && isset($data['items']) && isset($data['total'])) {
    // Crear conexión con la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Preparar la consulta para insertar datos
    $stmt = $conn->prepare("INSERT INTO pedidos_temp (reference_code, nombre_cliente, direccion, telefono, email, monto_total, items) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssssss", $data['referenceCode'], $data['nombre'], $data['direccion'], $data['telefono'], $data['email'], $data['total'], $data['items']);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Enviar respuesta JSON de éxito
            echo json_encode(['success' => true]);
        } else {
            // Enviar respuesta de error
            echo json_encode(['success' => false, 'message' => 'Error al guardar el pedido']);
        }
        
        // Cerrar la declaración
        $stmt->close();
    } else {
        // Enviar respuesta de error
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
    }

    // Cerrar la conexión
    $conn->close();
} else {
    // Enviar respuesta de error
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
