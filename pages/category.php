<?php
session_start();

function checkAuth()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit();
  }
}

checkAuth();

if (isset($_POST['logout'])) {
  session_destroy();
  header('Location: ./login.php');
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

if (isset($_GET['category'])) {
  $category = $_GET['category'];

  $categoryQuery = "SELECT id FROM categories WHERE name = '$category'";
  $categoryResult = $conn->query($categoryQuery);

  if ($categoryResult->num_rows > 0) {
    $categoryRow = $categoryResult->fetch_assoc();
    $categoryId = $categoryRow['id'];

    $serviceQuery = "SELECT * FROM services WHERE category_id = $categoryId";
    $serviceResult = $conn->query($serviceQuery);
  } else {

    header('Location: ./product.php');
    exit();
  }
} else {

  header('Location: ./product.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Foryou - <?php echo $category; ?> </title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <header>

  </header>

  <section class="search-container">

  </section>

  <main>

    <h1>Serviços da Categoria: <?php echo $category; ?></h1>

    <?php if (isset($serviceResult) && $serviceResult->num_rows > 0) : ?>
      <?php while ($row = $serviceResult->fetch_assoc()) : ?>
        <div class="service-card">
          <p>Nome do Serviço: <?php echo $row["service_name"]; ?></p>
          <p>Preço: R$ <?php echo number_format($row["price"], 2, ',', '.'); ?></p>
          <p>Descrição: <?php echo $row["description"]; ?></p>
        </div>
      <?php endwhile; ?>
    <?php else : ?>
      <p>Nenhum serviço encontrado nesta categoria.</p>
    <?php endif; ?>
  </main>

  <footer>
    &copy; Todos os direitos reservados.
  </footer>
</body>

</html>