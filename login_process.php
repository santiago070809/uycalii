<?php
session_start();

$correct_username = 'uycali2024'; 
$correct_password = 'uycali2024.';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $correct_username && $password === $correct_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['start'] = time(); 
        header("Location: agregar_productos.php"); 
        exit();
    } else {
        echo '<script>alert("Usuario o contraseña incorrectos."); window.location.href = "login.php";</script>';
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
