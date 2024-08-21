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

// Si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se seleccionó un libro
    if (!empty($_POST["libro_id"])) {
        $libro_id = $_POST["libro_id"];
        echo "Has seleccionado el libro con ID: $libro_id para préstamo.";
        echo "<br>";

    }
}

// Consulta SQL para obtener todos los libros disponibles
$sql = "SELECT id, titulo, autor, genero, disponibilidad FROM libros WHERE disponibilidad = 1";
$result = $conn->query($sql);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Libros</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: rgb(34,193,195);
            background: radial-gradient(circle, rgba(34,193,195,1) 0%, rgba(220,227,221,1) 41%, rgba(118,200,194,1) 68%, rgba(9,31,59,0.5692401960784313) 100%);
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
            color: white; 
        }

        td {
            background-color: #f9f9f9;
        }

        h2 {
            color: #1b004b; 
            padding: 10px; 
            text-align: left; 
        }

        nav {
            background-color: transparent; 
            padding: 10px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }

        nav form {
            display: inline-block; 
            margin-left: 10px; 
        }

        nav form input[type="submit"] {
            background-color: #003785; 
            color: white; 
            padding: 10px 15px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s; 
        }

        nav form input[type="submit"]:hover {
            background-color: #003785;
        }
        
    </style>
</head>
<body>

<nav>
    <h2>Catálogo de Libros Disponibles</h2>
    <form method='get' action='mis-libros.php'>
        <input type='submit' value='Mis Libros'>
    </form>
    <form method='get' action='formulario-libros.html'>
        <input type='submit' value='Agregar Libro'>
    </form>
</nav>
<?php
if ($result->num_rows > 0) {
    echo "<form method='post' action='prestamo-libro.php'>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Título</th><th>Autor</th><th>Género</th><th>Acción</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["titulo"] . "</td>";
        echo "<td>" . $row["autor"] . "</td>";
        echo "<td>" . $row["genero"] . "</td>";
        echo "<td><input type='radio' name='libro_id' value='" . $row["id"] . "'> Seleccionar para Préstamo</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br>";
    echo "<input type='submit' value='Enviar Solicitud de Préstamo' style='background-color: #003785; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;'>";
    echo "</form>";


} else {
    echo "No hay libros disponibles en el catálogo.";
}
?>

</body>
</html>
