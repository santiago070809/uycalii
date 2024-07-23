<?php
require_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    
    // Eliminar el producto de la base de datos
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error al eliminar el producto: " . $conn->error;
    }
} else {
    echo "Método no permitido";
}

$conn->close();
?>
