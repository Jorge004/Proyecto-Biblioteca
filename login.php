<?php
// Inicia la sesión
session_start();

// Verifica si el usuario ya está autenticado y redirige a la página principal
if (isset($_SESSION['usuario_id'])) {
    header("Location: catalogo-libros.php");
    exit();
}

// Verifica si se ha enviado el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Aquí deberías manejar la autenticación del usuario mediante consultas SQL a la base de datos
    $usuario = $_POST['usuario']; // Suponiendo que el formulario tiene un campo llamado "usuario"
    $contrasena = $_POST['contrasena']; // Suponiendo que el formulario tiene un campo llamado "contrasena"

    // Conexión a la base de datos (asegúrate de proporcionar tus propios datos de conexión)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "parcial_2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para buscar al usuario en la base de datos
    $sql = "SELECT id_usuario, nombre FROM usuarios WHERE nombre = '$usuario' AND contraseña = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si se encuentra un usuario con las credenciales proporcionadas
        $row = $result->fetch_assoc();

        // Guarda la información del usuario en la sesión
        $_SESSION['usuario_id'] = $row['id_usuario'];
        $_SESSION['usuario_nombre'] = $row['nombre'];

        // Redirige a la página principal después de iniciar sesión correctamente
        header("Location: mis-libros.php");
        exit();
    } else {
        // Mensaje de error si la autenticación falla
        $mensaje_error = "Usuario o contraseña incorrectos";
    }

    // Cierra la conexión a la base de datos
    $conn->close();
}
?>

<!-- El resto del código HTML permanece sin cambios -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Iniciar Sesión</title>
</head>
<body>

    <div class="container">
    <br><h1>Iniciar Sesión</h1>

    <?php
    // Muestra el mensaje de error (si existe)
    if (isset($mensaje_error)) {
        echo "<p style='color: red;'>$mensaje_error</p>";
    }
    ?>

    <form action="login.php" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <button type="submit">Iniciar Sesión</button>
    </form>
    </div>
</body>
</html>
