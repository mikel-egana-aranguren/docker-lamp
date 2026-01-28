<?php
session_start();

//Solo entrar a esta pagina si se entra con un metodo POST (mediante formulario)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: items.php');
  exit;
}

// Conexión DB
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
  http_response_code(500);
  echo "Error de conexión a la base de datos.";
  exit;
}
// validar el token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	http_response_code(403);
	die('Error: CSRF token inválido.');
	}

//Guardar datos
$titulo   = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$anio     = isset($_POST['anio']) ? trim($_POST['anio']) : '';
$director = isset($_POST['director']) ? trim($_POST['director']) : '';
$genero   = isset($_POST['genero']) ? trim($_POST['genero']) : '';
$duracion = isset($_POST['duracion']) ? trim($_POST['duracion']) : '';

$errors = [];

if (strlen($titulo) < 2) $errors[] = "El título debe tener al menos 2 caracteres.";
if (!preg_match('/^\d{4}$/', $anio) || (int)$anio < 1900 || (int)$anio > (int)date('Y')) $errors[] = "Año inválido.";
if (strlen($director) < 2) $errors[] = "El director debe tener al menos 2 caracteres.";
if (strlen($genero) < 2) $errors[] = "El género debe tener al menos 2 caracteres.";
if (!ctype_digit($duracion) || (int)$duracion <= 30 || (int)$duracion >= 52000 ) $errors[] = "La duración debe ser un número entero positivo asequible.";

//Convertir caracteres especiales de los errores en html
if (!empty($errors)) {
  foreach ($errors as $err) {
    echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . "<br>";
  }
  echo '<br><a href="items.php">Volver</a>';
  $conn->close();
  exit;
}

// Insertar
$stmt = $conn->prepare("INSERT INTO pelicula (titulo, anio, director, genero, duracion) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
  http_response_code(500);
  echo "Error en la consulta.";
  $conn->close();
  exit;
}

$anio_i = (int)$anio;
$duracion_i = (int)$duracion;
$stmt->bind_param("sissi", $titulo, $anio_i, $director, $genero, $duracion_i);

if ($stmt->execute()) {
  $stmt->close();
  $conn->close();
  header('Location: items.php');
  exit;
} else {
  echo "No se pudo guardar la película.";
  echo '<br><a href="items.php">Volver</a>';
  $stmt->close();
  $conn->close();
  exit;
}
?>