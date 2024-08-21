<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parcial_2";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del usuario actual
$id_usuario = $_SESSION['usuario_id'];

// Consulta SQL para obtener los libros prestados por el usuario actual
$sql = "SELECT libros.id as id_libro, libros.titulo, libros.autor, libros.genero, transaccion.fecha_devolucion
        FROM transaccion
        INNER JOIN libros ON transaccion.id_libro = libros.id
        WHERE transaccion.id_usuario = ? AND transaccion.fecha_devolucion IS NULL";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Mis Libros Prestados</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['titulo']} de {$row['autor']} ({$row['genero']}) - Fecha de Devolución: {$row['fecha_devolucion']} - <a href='devolucion-libro.php?id_libro={$row['id_libro']}'>Devolver</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No tienes libros prestados actualmente.</p>";
        echo "<a href='catalogo-libros.php'>Ir al Catálogo</a>";
    }

    $stmt->close();
} else {
    echo "Error en la preparación de la consulta: " . $conn->error;
}

// Cerrar la conexión
$conn->close();

// Agregar enlace para cerrar sesión
echo "<br><br>";
echo "<a href='cerrar-sesion.php'>Cerrar Sesión</a>";

echo "<br><br>";
echo "<a href='catalogo-libros.php'>Ir al Catálogo</a>";

?>
<html>
<head>
    <title>Mis Libros Prestados</title>
    <style>
        body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: rgb(229,228,233);
        background: linear-gradient(180deg, rgba(229,228,233,1) 42%, rgba(129,201,250,1) 82%);
        margin: 0;
        padding: 20px;
        }

        h2 {
            text-align: center;
            color: #003785;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        a {
            color: #003785;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
</style>        
</head>
</html>

