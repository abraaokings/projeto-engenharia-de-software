<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/register.css">
</head>

<body>
  <section class="login-form">
    <h1>Register</h1>
    <form class="form-wrapper" method="POST">
      <div class="form-name">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Jesus Cristo">
      </div>
      <div class="form-email">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="email@email.com">
      </div>
      <div class="form-password">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Coloque sua senha">
      </div>
      <button type="submit">Cadastrar</button>
      <p class="form-registro">Já tem uma conta? <a href="./login.php">Entrar</a></p>
    </form>
  </section>
</body>

</html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "foryou_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Use hash para armazenar senhas de forma segura

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "Registro bem-sucedido. Você pode fazer login agora.";
    } else {
        echo "Erro no registro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>