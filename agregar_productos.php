<?php
session_start();

// Verificar si la sesión está activa y no ha expirado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Si no hay sesión activa, redirigir a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Cerrar sesión al hacer clic en el botón de "Cerrar Sesión"
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>

    <!-- Estilos CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        form { max-width: 500px; margin: 0 auto; }
        label { display: block; margin-top: 10px; }
        input, button, select { width: 100%; padding: 10px; margin-top: 5px; }
        button { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .tab-content {
            background-color: white;
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 20px;
        }
        .product-item {
            background-color: #f1f3f5;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4">Gestión de Productos</h1>

    <!-- Formulario para cerrar sesión -->
    <form method="post" action="">
        <button type="submit" name="logout" class="btn btn-danger">Cerrar Sesión</button>
    </form>

    <!-- Tabs de categorías -->
    <ul class="nav nav-tabs" id="productTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="camisas-tab" data-toggle="tab" href="#camisas" role="tab">Camisas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="shorts-tab" data-toggle="tab" href="#shorts" role="tab">Shorts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="medias-tab" data-toggle="tab" href="#medias" role="tab">Medias</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="hoodies-tab" data-toggle="tab" href="#hoodies" role="tab">Hoodies</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="sudaderas-tab" data-toggle="tab" href="#sudaderas" role="tab">Sudaderas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="gorras-tab" data-toggle="tab" href="#gorras" role="tab">Gorras</a>
        </li>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="productTabContent">
        <?php
        $categories = ['camisas', 'shorts', 'medias', 'hoodies', 'sudaderas', 'gorras'];
        
        foreach ($categories as $category) {
            $active = ($category === 'camisas') ? 'show active' : '';
            echo "<div class='tab-pane fade $active' id='$category' role='tabpanel'>";
            echo "<h2 class='mb-4'>" . ucfirst($category) . "</h2>";
            
            // Formulario para agregar nuevo producto
            echo "<form id='{$category}Form' action='productos.php' method='POST' enctype='multipart/form-data'>";
            echo "<label for='{$category}ProductName'>Nombre del Producto:</label>";
            echo "<input type='text' id='{$category}ProductName' name='productName' required>";
            echo "<label for='{$category}ProductPrice'>Precio:</label>";
            echo "<input type='number' id='{$category}ProductPrice' name='productPrice' step='0.01' required>";
            echo "<input type='hidden' name='productCategory' value='$category'>";
            echo "<label for='{$category}ProductImage'>Imagen del Producto:</label>";
            echo "<input type='file' id='{$category}ProductImage' name='productImage' accept='image/*' required>";
            echo "<button type='submit'>Agregar Producto</button>";
            echo "</form>";
            
            // Lista de productos existentes
            echo "<div id='{$category}List'>";
            require_once "db_connection.php";
            $sql = "SELECT * FROM productos WHERE category = '$category'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product-item' data-id='{$row['id']}'>";
                    echo "<h5>{$row['nombre']}</h5>";
                    echo "<p>Precio: $" . number_format($row['precio'], 2) . "</p>";
                    echo "<img src='{$row['imagen']}' alt='{$row['nombre']}' style='max-width: 100px;'>";
                    echo "<div class='mt-2'>";
                    echo "<button class='btn btn-sm btn-info edit-product'>Editar</button> ";
                    echo "<button class='btn btn-sm btn-danger delete-product' data-id='{$row['id']}'>Eliminar</button>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay productos disponibles en esta categoría.</p>";
            }
            echo "</div>";
            
            echo "</div>";
        }
        $conn->close();
        ?>
    </div>
</div>

<!-- Modal para editar producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="editProductId" name="id">
                    <div class="form-group">
                        <label for="editProductName">Nombre del Producto</label>
                        <input type="text" class="form-control" id="editProductName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductPrice">Precio</label>
                        <input type="number" class="form-control" id="editProductPrice" name="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductImage">Nueva Imagen (opcional)</label>
                        <input type="file" class="form-control-file" id="editProductImage" name="image">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveEditProduct">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Editar producto - Abrir modal y cargar datos
        $('.edit-product').click(function() {
            var product = $(this).closest('.product-item');
            var productId = product.data('id');
            var productName = product.find('h5').text().trim();
            var productPrice = parseFloat(product.find('p').text().replace('Precio: $', '').replace(',', ''));
            
            $('#editProductId').val(productId);
            $('#editProductName').val(productName);
            $('#editProductPrice').val(productPrice);
            
            $('#editProductModal').modal('show');
        });

        // Eliminar producto
        $('.delete-product').click(function() {
            var productId = $(this).data('id'); // Obtener el ID del producto a eliminar

            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                $.ajax({
                    url: 'delete_product.php',
                    type: 'POST',
                    data: { id: productId },
                    success: function(response) {
                        // Manejar la respuesta del servidor
                        if (response === 'success') {
                            // Eliminar visualmente el producto eliminado
                            $('[data-id="' + productId + '"]').closest('.product-item').remove();
                            location.reload(); // Recargar la página después de eliminar
                        } else {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Error al conectar con el servidor. Por favor, intenta de nuevo más tarde.');
                    }
                });
            }
        });

 // Guardar cambios en la edición de producto
 $('#saveEditProduct').click(function() {
            var form = $('#editProductForm')[0];
            var formData = new FormData(form);

            $.ajax({
                url: 'update_product.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Manejar respuesta, por ejemplo cerrar el modal y actualizar la lista de productos
                    $('#editProductModal').modal('hide');
                    
                    location.reload();
                }
            });
            

        });    });
</script>

</body>
</html>
