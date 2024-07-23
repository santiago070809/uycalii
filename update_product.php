<?php
require_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    
    $sql = "UPDATE productos SET nombre = ?, precio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $name, $price, $id);
    
    if ($stmt->execute()) {
        // Si se subió una nueva imagen
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            // Verificar el tipo de archivo
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    // Actualizar la ruta de la imagen en la base de datos
                    $sql = "UPDATE productos SET imagen = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $target_file, $id);
                    $stmt->execute();
                }
            }
        }
        echo "Producto actualizado exitosamente";
    } else {
        echo "Error al actualizar el producto: " . $conn->error;
    }
} else {
    echo "Método no permitido";
}

$conn->close();
?>