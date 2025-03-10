<?php
// Credenciales correctas para Amazon RDS
$servername = "telemedicinadb.cluster-cr28uag0qtfn.us-east-2.rds.amazonaws.com"; // El endpoint de tu RDS
$username = "admin";  // Tu usuario de RDS
$password = "Bisiclet981";  // Tu contraseña de RDS
$dbname = "chatmedicodb";  // Nombre de la base de datos en RDS

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    // Cifrar la contraseña antes de guardarla
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
    
    // Usar consulta preparada para evitar inyecciones SQL
    $sql_check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql_check->bind_param("s", $user);
    $sql_check->execute();
    $result = $sql_check->get_result();
    
    if ($result->num_rows > 0) {
        // Si el usuario ya existe
        echo json_encode(['success' => false, 'message' => 'Usuario ya existe']);
    } else {
        // Insertar el nuevo usuario
        $sql = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $sql->bind_param("ss", $user, $hashed_pass);
        
        if ($sql->execute()) {
            echo json_encode(['success' => true, 'message' => '¡Usuario creado con éxito!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear usuario']);
        }
    }

    $sql_check->close();
    $sql->close();
}

$conn->close();
?>
