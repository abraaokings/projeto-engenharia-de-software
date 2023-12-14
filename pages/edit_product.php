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

$userId = $_SESSION['user_id'];

$productQuery = "SELECT * FROM services WHERE user_id = $userId";
$productResult = $conn->query($productQuery);

$products = [];

if ($productResult->num_rows > 0) {
  while ($row = $productResult->fetch_assoc()) {
    $products[] = $row;
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Produtos</title>
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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    a {
      color: #007dfa;
      text-decoration: none;
      cursor: pointer;
    }

    a:hover {
      text-decoration: underline;
    }

    p {
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <a style="text-decoration: none;" href="../index.php">Voltar</a>
    <h1>Lista de Produtos</h1>

    <?php if (count($products) > 0) : ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome do Serviço</th>
            <th>Preço</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) : ?>
            <tr>
              <td><?php echo $product['id']; ?></td>
              <td><?php echo $product['service_name']; ?></td>
              <td>R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
              <td><?php echo $product['description']; ?></td>
              <td><?php echo $product['category_name']; ?></td>
              <td>
                <a href="edit_product.php?id=<?php echo $product['id']; ?>">Editar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <p>Nenhum produto encontrado.</p>
    <?php endif; ?>
  </div>
</body>

</html>