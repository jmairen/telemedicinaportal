<?php
// Credenciales correctas para Amazon RDS
$host = "telemedicinadb.cluster-cr28uag0qtfn.us-east-2.rds.amazonaws.com";
$usuario = "admin"; 
$clave = "Bisiclet981";
$base_de_datos = "chatmedicodb";
$puerto = "3306";

// Conectar a la base de datos
$conexion = new mysqli($host, $usuario, $clave, $base_de_datos, $puerto);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error . " (Código: " . $conexion->connect_errno . ")");
}
echo "Conexión exitosa a la base de datos.\n";

// Array de correos y contraseñas a hashear
$passwords = [
    "juan@example.com" => "1234",
    "maria@example.com" => "5678",
    "admin@example.com" => "1234"
];

// Preparar y ejecutar la actualización para cada usuario
foreach ($passwords as $email => $password) {
    // Generar el hash de la contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Preparar la consulta SQL
    $sql = "UPDATE usuarios SET contraseña = ? WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt === false) {
        echo "Error al preparar la consulta para $email: " . $conexion->error . "\n";
        continue;
    }
    
    // Bind y ejecutar
    $stmt->bind_param("ss", $hash, $email);
    if ($stmt->execute()) {
        echo "Actualizado usuario $email con hash correctamente.\n";
    } else {
        echo "Error al actualizar usuario $email: " . $stmt->error . "\n";
    }
    
    $stmt->close();
}

// Cerrar la conexión
$conexion->close();
echo "Proceso completado.";
?>