<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "foryou_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
  $searchTerm = $_GET['search'];

  $searchQuery = "SELECT * FROM services WHERE service_name LIKE '%$searchTerm%'";
  $searchResult = $conn->query($searchQuery);

  $searchProducts = [];

  if ($searchResult->num_rows > 0) {
    while ($row = $searchResult->fetch_assoc()) {
      $searchProducts[] = $row;
    }
  }
} else {
  header('Location: ./index.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resultados da Pesquisa</title>
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

    .search-results {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .product-card {
      border: 1px solid #ddd;
      padding: 16px;
      width: 300px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    img {
      max-width: 100%;
      height: auto;
      border-radius: 4px;
      margin-top: 16px;
    }
  </style>
</head>

<body>
  <div class="container">
    <a style="text-decoration: none;" href="../index.php">Voltar</a>
    <h1>Resultados da Pesquisa</h1>

    <?php if (count($searchProducts) > 0) : ?>
      <div class="search-results">
        <?php foreach ($searchProducts as $product) : ?>
          <div class="product-card">
            <p>Service Name: <?php echo $product["service_name"]; ?></p>
            <p>Category: <?php echo $product["category_name"]; ?></p>
            <p>Price: R$ <?php echo number_format($product["price"], 2, ',', '.'); ?></p>
            <p>Description: <?php echo $product["description"]; ?></p>
            <img src="<?php echo $product["image_path"]; ?>" alt="Service Image">
          </div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p>Nenhum serviço encontrado para a pesquisa: <?php echo $searchTerm; ?></p>
    <?php endif; ?>
  </div>
</body>

</html>