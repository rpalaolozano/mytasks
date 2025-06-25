<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = getenv('MYSQL_HOST');
$db   = getenv('MYSQL_DB');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASS');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Conexión a la base de datos OK.<br>";

    // Datos de prueba:
    $title = "Tarea de prueba";
    $description = "Descripción de prueba";
    $username = "admin"; // Debe existir en tabla users

    // Preparar la inserción con el user_id obtenido de username
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description, user_id) VALUES (?, ?, (SELECT id FROM users WHERE username = ? LIMIT 1))");
    $stmt->execute([$title, $description, $username]);

    echo "Inserción realizada correctamente.<br>";

    // Mostrar última tarea insertada:
    $id = $pdo->lastInsertId();
    $stmt2 = $pdo->prepare("SELECT t.id, t.title, t.description, t.completed, u.username FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
    $stmt2->execute([$id]);
    $task = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($task) {
        echo "<pre>";
        print_r($task);
        echo "</pre>";
    } else {
        echo "No se encontró la tarea insertada.";
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
