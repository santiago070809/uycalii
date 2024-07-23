<?php
// respuesta_payu.php

// Obtener los datos enviados por GET o POST
$referenceCode = $_POST['referenceCode'] ?? $_GET['referenceCode'] ?? '';

// Verificar y depurar los datos recibidos
if (empty($referenceCode)) {
    // Mostrar todos los datos recibidos para depuración
    echo 'Código de referencia no proporcionado. Datos recibidos: <pre>';
    print_r($_POST);
    print_r($_GET);
    echo '</pre>';
    exit;
}

// Redirigir a confirmacion_payu.php con el código de referencia
header("Location: http://localhost:3000/public_html/confirmacion_payu.php?referenceCode=" . urlencode($referenceCode));
exit;
?>
