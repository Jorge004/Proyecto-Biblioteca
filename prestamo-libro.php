<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redirigir a la página de inicio de sesión si el usuario no ha iniciado sesión
    exit();
}

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

// Verificar si se ha enviado el formulario y si se ha seleccionado un libro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["libro_id"])) {
    if (isset($_SESSION['usuario_id']) && is_numeric($_SESSION['usuario_id'])) {
    $id_usuario = $_SESSION['usuario_id'];
    $libro_id = $_POST["libro_id"];  


    $sql_disponibilidad = "SELECT disponibilidad FROM libros WHERE id = $libro_id";
    $result_disponibilidad = $conn->query($sql_disponibilidad);

    if ($result_disponibilidad->num_rows > 0) {
        $row = $result_disponibilidad->fetch_assoc();
        $disponibilidad = $row["disponibilidad"];

        if ($disponibilidad == 1) {
            // Actualizar la disponibilidad del libro en la base de datos
            $sql_actualizar_disponibilidad = "UPDATE libros SET disponibilidad = 0 WHERE id = $libro_id";
            $conn->query($sql_actualizar_disponibilidad);

            // Registrar la transacción en la tabla de transacciones
            $sql = "INSERT INTO transaccion (id_usuario, id_libro) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            
            // Comprobar si la preparación fue exitosa
            if ($stmt) {
                // Vincular los parámetros y ejecutar la consulta
                $stmt->bind_param("ii", $id_usuario, $libro_id);

                if ($stmt->execute()) {
                    echo "Has seleccionado el libro con ID: $libro_id para préstamo.";
                    echo "<br>" . "<br>";
                    echo "<a href='catalogo-libros.php' class='button'>Volver al catálogo de libros</a>";
                } else {
                    echo "Error al ejecutar la consulta: " . $stmt->error;
                }
                
                $stmt->close();
            } else {
                echo "Error en la preparación de la consulta: " . $conn->error;
            }
        } else {
            echo "El libro seleccionado no está disponible.";
            echo "<br>" . "<br>";
            echo "<a href='catalogo-libros.php' class='button'>Volver al catálogo de libros</a>";
        }
    } else {
        echo "Error al verificar la disponibilidad del libro.";
    }

}  else {
    echo "Por favor, selecciona un libro antes de enviar la solicitud.";

}
}


// Cerrar la conexión
$conn->close();
?>
<html>
<head>
    <title>Préstamo de Libros</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: rgb(34,193,195);
            background: radial-gradient(circle, rgba(34,193,195,1) 0%, rgba(220,227,221,1) 41%, rgba(118,200,194,1) 68%, rgba(9,31,59,0.5692401960784313) 100%);
        }

        h2 {
            margin-top: 0;
        }

        button {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 48%;
            margin-top: 10px; 
        }

        button:hover {
            background-color: #047af0;
        }

        a.button {
            display: left;
            background-color: #047af0;
            color: white;
            padding: 12px 20px;
            text-align: left;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            margin-top: 10px; 
        }

        a.button:hover {
            background-color: #047af0;
        }

    </style>
</head>
</html>
