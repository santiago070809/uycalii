<?php
// Procesar el pedido y redirigir al usuario a PayU

// Configura tu conexión a la base de datos
$host = 'localhost'; // Cambia estos valores según tu configuración
$db = 'uycali';
$user = 'root';
$pass = '';

$pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Recoger los datos del formulario
$payerFullName = $_POST['payerFullName'];
$shippingAddress = $_POST['shippingAddress'];
$telephone = $_POST['telephone'];
$buyerEmail = $_POST['buyerEmail'];
$referenceCode = $_POST['referenceCode'];
$amount = $_POST['amount'];
$currency = $_POST['currency'];
$signature = $_POST['signature'];

// Inserta los datos en la tabla `pedidos`
$sql = "INSERT INTO pedidos (payerFullName, shippingAddress, telephone, buyerEmail, referenceCode, amount, currency, signature) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$payerFullName, $shippingAddress, $telephone, $buyerEmail, $referenceCode, $amount, $currency, $signature]);

// Redirige al usuario a PayU
header("Location: https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/");
exit();
?>
