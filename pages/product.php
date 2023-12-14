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
?>

<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "foryou_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
        main {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 16px;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            margin: 16px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-top: 16px;
        }


        .logo {
            font-family: "Pixelify Sans", Courier, monospace;
            font-size: 24px;
            text-decoration: none;
            color: #222;
        }

        header {
            border-bottom: 2px solid #ccc;

        }

        header nav {
            display: flex;
            justify-content: space-between;
            width: 900px;
            margin: 0 auto;
        }

        header nav ul {
            display: flex;
            gap: 20px;
            align-items: center;
            list-style: none;
        }

        header nav a {
            text-decoration: none;
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
                            <a href="./edit_product.php">Meus produtos</a>
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

    <main>
        <div>
            <h2>Filtrar por Preço</h2>
            <ul>
                <li><a href="?filter=low_to_high">Menor Preço</a></li>
                <li><a href="?filter=high_to_low">Maior Preço</a></li>
            </ul>
        </div>

        <?php
        $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

        $sql = "SELECT * FROM services";

        if ($filter === 'low_to_high') {
            $sql .= " ORDER BY price ASC";
        } elseif ($filter === 'high_to_low') {
            $sql .= " ORDER BY price DESC";
        }

        $result = $conn->query($sql);
        ?>

        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="card">
                    <p>Service Name: <?php echo $row["service_name"]; ?></p>
                    <p>Category: <?php echo $row["category_name"]; ?></p>
                    <p>Price: <?php echo $row["price"]; ?></p>
                    <p>Description: <?php echo $row["description"]; ?></p>
                    <img src="<?php echo $row["image_path"]; ?>" alt="Service Image">
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>No results found.</p>
        <?php endif; ?>
    </main>

</body>

</html>

<?php
$conn->close();
?>