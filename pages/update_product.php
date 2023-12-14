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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
  $productId = $_POST['product_id'];
  $service_name = $_POST['service_name'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  $category_name = $_POST['category_name'];

  $service_name = mysqli_real_escape_string($conn, $service_name);
  $description = mysqli_real_escape_string($conn, $description);
  $category_name = mysqli_real_escape_string($conn, $category_name);

  $updateQuery = "UPDATE services SET service_name = '$service_name', price = $price, description = '$description', category_name = '$category_name' WHERE id = $productId AND user_id = {$_SESSION['user_id']}";

  if ($conn->query($updateQuery) === TRUE) {
    header('Location: ./lista_produtos.php');
    exit();
  } else {
    echo "Erro ao atualizar o produto: " . $conn->error;
  }
} else {
  header('Location: ./lista_produtos.php');
  exit();
}

$conn->close();
