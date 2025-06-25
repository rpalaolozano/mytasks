<?php
$host = getenv('MYSQL_HOST');
$db   = getenv('MYSQL_DB');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASS');
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents("php://input"), true);
  $stmt = $pdo->prepare("INSERT INTO tasks (title, description, user_id) VALUES (?, ?, (SELECT id FROM users WHERE username = ? LIMIT 1))");
  $stmt->execute([$data['title'], $data['description'], $data['user']]);
  echo json_encode(['status' => 'success']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
  $data = json_decode(file_get_contents("php://input"), true);
  $stmt = $pdo->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
  $stmt->execute([$data['completed'], $data['id']]);
  echo json_encode(['status' => 'updated']);
} else {
  $stmt = $pdo->query("SELECT t.id, t.title, t.description, t.completed, u.username as user FROM tasks t JOIN users u ON t.user_id = u.id");
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
?>
