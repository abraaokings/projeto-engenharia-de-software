<?php
session_start();

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ./pages/login.php');
        exit();
    }
}

checkAuth();

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ./pages/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foryou</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <header>
        <nav>
            <a href="./pages/product.php" class="logo">Foryou</span>
            <ul>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li>
                        <span class="add-post">
                            <img src="./assets/img/add.svg" alt="">
                            <a href="./pages/add-service.php">Adicionar serviço</a>
                        </span>
                    </li>
                    <li>
                        <span class="logout">
                            <form method="post">
                                <button type="submit" name="logout">Sair</button>
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

    <section class="search-container">
        <div class="input-search">
            <img src="./img/search.svg" alt="">
            <input type="search" placeholder="procurar serviços">
        </div>
        <h1 class="search-categoria-title">Categorias</h1>
        <div class="search-categorias">
            <span class="cards">Eletricista</span>
            <span class="cards">Encanador</span>
            <span class="cards">Jardineiro</span>
            <span class="cards">Chef de Cozinha</span>
            <span class="cards">Marceneiro</span>
            <span class="cards">Pintor</span>
            <span class="cards">Professor Particular</span>
            <span class="cards">Técnico de Informática</span>
            <span class="cards">Personal Trainer</span>
            <span class="cards">Fotógrafo</span>
            <span class="cards">Massagista</span>
        </div>
    </section>

    <footer>
        &copy; Todos os direitos reservados.
    </footer>
</body>

</html>
