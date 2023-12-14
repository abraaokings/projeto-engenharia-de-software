<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ./pages/login.php');
  exit();
}

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "foryou_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
  $productId = $_GET['id'];

  // Verificar se o produto pertence ao usuário autenticado
  $userId = $_SESSION['user_id'];
  $checkOwnershipQuery = "SELECT * FROM services WHERE id = $productId AND user_id = $userId";
  $ownershipResult = $conn->query($checkOwnershipQuery);

  if ($ownershipResult->num_rows > 0) {
    // O usuário é o proprietário do produto, pode excluí-lo
    $deleteQuery = "DELETE FROM services WHERE id = $productId";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
      // Produto excluído com sucesso
      header('Location: ./lista_produtos.php');
      exit();
    } else {
      // Erro ao excluir o produto
      echo "Erro ao excluir o produto.";
    }
  } else {
    // Produto não pertence ao usuário, redirecionar
    header('Location: ./lista_produtos.php');
    exit();
  }
} else {
  // Redirecionar se não houver ID fornecido
  header('Location: ./lista_produtos.php');
  exit();
}

$conn->close();
