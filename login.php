<?php
// Credenciales de Amazon RDS
$host = "telemedicinadb.cluster-cr28uag0qtfn.us-east-2.rds.amazonaws.com"; // Reemplaza con tu endpoint de RDS
$usuario = "admin"; // Tu usuario de RDS
$clave = "Bisiclet981"; // Tu contraseña de RDS
$dbname = "chatmedicodb"; // Nombre de la base de datos en RDS
$puerto = "3306"; // El puerto predeterminado de MySQL es 3306

// Crear conexión a Amazon RDS
$conn = new mysqli($host, $usuario, $clave, $dbname, $puerto);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error . " (Código: " . $conn->connect_errno . ")");
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
