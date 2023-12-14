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
    // O usuário é o proprietário do produto, pode editar
    $editQuery = "SELECT * FROM services WHERE id = $productId";
    $editResult = $conn->query($editQuery);

    if ($editResult->num_rows > 0) {
      $editedProduct = $editResult->fetch_assoc();
    } else {
      // Produto não encontrado
      header('Location: ./lista_produtos.php');
      exit();
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Produto</title>
  <link rel="stylesheet" href="styles.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      color: #333;
    }

    .container {
      max-width: 800px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #007dfa;
    }

    form {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
    }

    input {
      width: 100%;
      padding: 8px;
      margin-bottom: 16px;
    }

    button {
      padding: 8px 12px;
      border: none;
      background-color: #007dfa;
      color: #fff;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="container">
    <a style="text-decoration: none;" href="./lista_produtos.php">Voltar</a>
    <h1>Editar Produto</h1>

    <form action="../pages/update_product.php" method="POST">
      <input type="hidden" name="product_id" value="<?php echo $editedProduct['id']; ?>">

      <label for="service_name">Nome do Serviço:</label>
      <input type="text" name="service_name" value="<?php echo $editedProduct['service_name']; ?>" required>

      <label for="price">Preço:</label>
      <input type="number" name="price" value="<?php echo $editedProduct['price']; ?>" required>

      <label for="description">Descrição:</label>
      <textarea name="description" required><?php echo $editedProduct['description']; ?></textarea>

      <label for="category_name">Categoria:</label>
      <input type="text" name="category_name" value="<?php echo $editedProduct['category_name']; ?>" required>

      <button type="submit">Salvar Alterações</button>
    </form>
  </div>
</body>

</html>