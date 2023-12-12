<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <section class="login-form">
        <h1>Login</h1>
        <form class="form-wrapper" method="post">
            <div class="form-email">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="email@email.com">
            </div>
            <div class="form-password">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Coloque sua senha">
            </div>
            <button type="submit">Entrar</button>
            <p class="form-registro">Não possui uma conta? <a href="./register.php">Registrar</a></p>
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

    $email = $_POST["email"];
    $passwordInput = $_POST["password"];

    $query = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId, $userName, $userEmail, $hashedPassword);

    if ($stmt->fetch() && password_verify($passwordInput, $hashedPassword)) {
        session_start();
        $_SESSION['user_id'] = $userId;  // Defina a variável de sessão

        header('Location: ../');
        exit();
    } else {
        echo "Login falhou. Verifique suas credenciais.";
    }

    $stmt->close();
    $conn->close();
}
?>