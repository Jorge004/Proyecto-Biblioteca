<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros Prestados</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: rgb(34,193,195);
            background: radial-gradient(circle, rgba(34,193,195,1) 0%, rgba(220,227,221,1) 41%, rgba(118,200,194,1) 68%, rgba(9,31,59,0.5692401960784313) 100%);
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        select {
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px;
            background-color: #bc4ed8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #bc4ed8;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #bc4ed8;
        }
    </style>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parcial_2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del usuario actual
$usuario_id = $_SESSION['usuario_id'];

// Consultar los libros prestados por el usuario
$sql_libros_prestados = "SELECT libros.id, libros.titulo, transaccion.fecha_prestamo
                         FROM transaccion
                         INNER JOIN libros ON transaccion.id_libro = libros.id
                         WHERE transaccion.id_usuario = $usuario_id";
$result_libros_prestados = $conn->query($sql_libros_prestados);

// Verificar si hay libros prestados
if ($result_libros_prestados->num_rows > 0) {
    echo "<h2>Libros Prestados</h2>";
    echo "<form action='devolucion-libro.php' method='post'>";
    echo "<label for='libro_id'>Seleccione el libro a devolver:</label>";
    echo "<select name='libro_id' id='libro_id'>";

    while ($row_libro_prestado = $result_libros_prestados->fetch_assoc()) {
        echo "<option value='" . $row_libro_prestado["id"] . "'>" . $row_libro_prestado["titulo"] . " - Prestado el " . $row_libro_prestado["fecha_prestamo"] . "</option>";
    }

    echo "</select>";
    echo "<button type='submit'>Devolver libro</button>";
    echo "</form>";

    echo "<form action='catalogo-libros.php' method='post'>";
    echo "<button type='submit'>Volver al Catálogo</button>";
    echo "</form>";


    // Procesar el formulario de devolución de libros
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $libro_id_devolucion = $_POST["libro_id"];

        // Verificar si el libro está prestado al usuario
        $sql_verificar_prestamo = "SELECT id_transaccion FROM transaccion WHERE id_usuario = $usuario_id AND id_libro = $libro_id_devolucion";
        $result_verificar_prestamo = $conn->query($sql_verificar_prestamo);

        if ($result_verificar_prestamo->num_rows > 0) {
            // El libro está prestado al usuario, proceder con la devolución

            // Actualizar la disponibilidad del libro en la tabla de libros
            $sql_actualizar_disponibilidad = "UPDATE libros SET disponibilidad = 1 WHERE id = $libro_id_devolucion";
            $conn->query($sql_actualizar_disponibilidad);

            // Eliminar la transacción de préstamo
            $sql_eliminar_prestamo = "DELETE FROM transaccion WHERE id_usuario = $usuario_id AND id_libro = $libro_id_devolucion";
            $conn->query($sql_eliminar_prestamo);

            echo "<p>Libro devuelto con éxito.</p>";
        } else {
            echo "<p>Error: El libro no está prestado al usuario.</p>";
        }
    }
} else {
    echo "<p>No tienes libros prestados en este momento.</p>";
}

$conn->close();
?>
</body>
</html>
