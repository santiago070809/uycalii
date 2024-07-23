<?php
// Conecta a la base de datos
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Verifica la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Recibe los datos del formulario
$cart = json_decode($_POST['cart'], true);
$referenceCode = $_POST['referenceCode'];
$amount = $_POST['amount'];
$signature = $_POST['signature'];

// Inserta los datos en la tabla pedidos_temp
$query = "INSERT INTO pedidos_temp (referenceCode, amount, cart) VALUES ('$referenceCode', '$amount', '$cart')";
mysqli_query($conn, $query);

// Cierra la conexión
mysqli_close($conn);
?>