<?php
// Conexión a la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_db = 'parcial_2';

$con_bd = mysqli_connect(
    $db_host,
    $db_user,
    $db_password,
    $db_db
);

// Verificar la conexión
if (!$con_bd) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Recibir datos del formulario
$titulo = $_POST['titulo'];
$autor = $_POST['autor'];
$genero = $_POST['genero'];

// Insertar libro en la base de datos
$sql = "INSERT INTO libros (titulo, autor, genero) VALUES ('$titulo', '$autor', '$genero')";

if (mysqli_query($con_bd, $sql)) {
    echo "Libro registrado con éxito.";
    echo "<br>" . "<br>";
    echo "<a href='catalogo-libros.php' class='button'>Volver al catálogo de libros</a>";
} else {
    echo "Error al registrar el libro: " . mysqli_error($con_bd);
    echo "<br>" . "<br>";
    echo "<a href='catalogo-libros.php' class='button'>Volver al catálogo de libros</a>";
}

// Cerrar la conexión
mysqli_close($con_bd);
?>
<html>
<head>
    <title>Registrar Libro</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: rgb(34,193,195);
            background: radial-gradient(circle, rgba(34,193,195,1) 0%, rgba(220,227,221,1) 41%, rgba(118,200,194,1) 68%, rgba(9,31,59,0.5692401960784313) 100%);
        }
        
        .button {
            background-color: #bc4ed8;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }
        
        .button:hover {
            background-color: #bc4ed8;
        }
    </style>
</head>
</html>
