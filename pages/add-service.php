<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

function checkAuth()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit();
  }
}

checkAuth();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $servername = "127.0.0.1";
  $username = "root";
  $password = "";
  $dbname = "foryou_db";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $serviceName = $_POST["name-service"];
  $categoryName = $_POST["category-service"];
  $price = $_POST["preco-service"];
  $description = $_POST["desc-service"];

  $imagePath = "";
  if (isset($_FILES["foto-service"])) {
    $imageName = $_FILES["foto-service"]["name"];
    $imagePath = $imageName;

    if (move_uploaded_file($_FILES["foto-service"]["tmp_name"], $imagePath)) {
      echo "Arquivo enviado com sucesso.";
    } else {
      echo "Erro ao mover o arquivo.";
    }
  } else {
    echo "Nenhum arquivo enviado.";
  }

  if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null) {
    $categoryQuery = "INSERT IGNORE INTO categories (name) VALUES (?)";
    $categoryStmt = $conn->prepare($categoryQuery);
    $categoryStmt->bind_param("s", $categoryName);
    $categoryStmt->execute();
    $categoryStmt->close();

    $categoryId = 0;
    $categoryQuery = "SELECT id FROM categories WHERE name = ?";
    $categoryStmt = $conn->prepare($categoryQuery);
    $categoryStmt->bind_param("s", $categoryName);
    $categoryStmt->execute();
    $categoryStmt->bind_result($categoryId);
    $categoryStmt->fetch();
    $categoryStmt->close();

    $stmt = $conn->prepare("INSERT INTO services (service_name, image_path, price, description, category_name, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssi", $serviceName, $imagePath, $price, $description, $categoryName, $_SESSION['user_id']);

    if ($stmt->execute()) {
      echo "Service added successfully";
    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
  } else {
    echo "Erro: Usuário não autenticado.";
  }

  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adicionar serviço</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: "Poppins", sans-serif;
      box-sizing: border-box;
    }

    body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100vh;
    }

    .logo {
      font-family: "Pixelify Sans", Courier, monospace;
      font-size: 24px;
    }

    header {
      border-bottom: 1px solid #c3ccd9;
    }

    header nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1080px;
      margin: 10px auto;
    }

    header nav li {
      list-style: none;
    }

    header nav ul {
      display: flex;
      align-items: center;
      gap: 29px;
    }

    header .add-post,
    header .login {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    header a {
      text-decoration: none;
      color: #282828;
    }

    header .login {
      background-color: #007dfa;
      padding: 8px 20px;
      border-radius: 8px;
    }

    header .login:hover {
      background-color: #1e8dfd;
    }

    header .login a {
      color: #ffffff;
    }

    .profile-cover {
      display: inline-block;
      width: 100px;
      height: 100px;
      border-radius: 100%;
      background-color: #282828;
    }

    .add-service-container {
      margin: 10px auto;
      max-width: 1080px;
    }

    .add-service-container h1 {
      margin: 10px 0;
    }

    .add-service {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      width: 100%;
    }

    .form {
      display: flex;
      flex-direction: column;
      max-width: 400px;
      width: 100%;
      margin: 0 auto;
    }

    .form label {
      margin-bottom: 5px;
      font-weight: 600;
    }

    .form input,
    .form textarea {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .buttons {
      display: flex;
      justify-content: space-between;
    }

    button {
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      color: #fff;
      background-color: #333;
    }

    footer {
      text-align: center;
      padding: 10px 0;
      background-color: #333;
      color: #fff;
    }
  </style>
</head>

<body>
  <header>
    <nav>
      <a class="logo" href="../index.php">Foryou</span>
        <ul>
          <?php if (isset($_SESSION['user_id'])) : ?>
            <li>
              <span class="add-post">
                <a href="./add-service.php">Adicionar serviço</a>
              </span>
            </li>
            <li>
              <a href="./product.php">Feed</a>
            </li>
            <li>
              <a href="./lista_produtos.php">Meus produtos</a>
            </li>
            <li>
              <span class="logout">
                <form method="post">
                  <button style="padding: 8px 12px; border: none; background-color: #222; border-radius: 8px; color: #fff;" type="submit" name="logout">Sair</button>
                </form>
              </span>
            </li>
          <?php else : ?>
            <li>
              <span class="register">
                <a href="./pages/register.php">Cadastrar</a>
              </span>
            </li>
            <li>
              <span class="login">
                <a href="./pages/login.php">Entrar</a>
              </span>
            </li>
          <?php endif; ?>
        </ul>
    </nav>
  </header>

  <section class="add-service-container">
    <h1>Adicionar serviço</h1>
    <div class="add-service">
      <form class="form" method="POST" enctype="multipart/form-data">
        <div class="name-service">
          <label for="name-service">Nome do serviço</label>
          <input type="text" id="name-service" name="name-service">
        </div>
        <div class="category-service">
          <label for="category-service">Categoria</label>
          <select id="category-service" name="category-service">
            <option value="Eletricista">Eletricista</option>
            <option value="Encanador">Encanador</option>
            <option value="Jardineiro">Jardineiro</option>
            <option value="Chef de Cozinha">Chef de Cozinha</option>
            <option value="Marceneiro">Marceneiro</option>
            <option value="Pintor">Pintor</option>
            <option value="Professor Particular">Professor Particular</option>
            <option value="Técnico de Informática">Técnico de Informática</option>
            <option value="Personal Trainer">Personal Trainer</option>
            <option value="Fotógrafo">Fotógrafo</option>
            <option value="Massagista">Massagista</option>
          </select>
        </div>
        <div class="foto-service">
          <label for="foto-service">Imagem do serviço</label>
          <input type="file" id="foto-service" name="foto-service">
        </div>
        <div class="preco-service">
          <label for="preco-service">Preço</label>
          <input type="number" id="preco-service" name="preco-service">
        </div>
        <div class="desc-service">
          <label for="desc-service">Descrição do serviço</label>
          <textarea id="desc-service" name="desc-service" rows="4"></textarea>
        </div>
        <div class="buttons">
          <button type="button">Voltar</button>
          <button type="submit">Publicar</button>
        </div>
      </form>
    </div>
  </section>

  <footer>&copy; Todos os direitos reservados.</footer>
</body>

</html>