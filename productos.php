<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'db_connection.php';

    $productName = mysqli_real_escape_string($conn, $_POST['productName']);
    $productPrice = $_POST['productPrice'];
    $productCategory = $_POST['productCategory'];
    $productImage = $_FILES['productImage']['name']; 
    $imageTmpName = $_FILES['productImage']['tmp_name']; 

    $targetDir = "uploads/"; 
    $targetFilePath = $targetDir . basename($productImage);

    if (move_uploaded_file($imageTmpName, $targetFilePath)) {
        $sql = "INSERT INTO productos (nombre, precio, imagen, category) 
                VALUES ('$productName', '$productPrice', '$targetFilePath', '$productCategory')";

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            header("Location: agregar_productos.php?message=Producto%20agregado%20con%20éxito");
            exit();
        } else {
            echo "Error al insertar el producto: " . $conn->error;
        }
    } else {
        echo "Error al subir la imagen.";
    }

    $conn->close();
}
?>
