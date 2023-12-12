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
    <style>
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            margin: 16px;
            width: 300px;
            float: left;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <h1>Service Details</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card">
                <p>Service Name: <?php echo $row["service_name"]; ?></p>
                <p>Category: <?php echo $row["category_name"]; ?></p>
                <p>Price: <?php echo $row["price"]; ?></p>
                <p>Description: <?php echo $row["description"]; ?></p>
                <img src="<?php echo $row["image_path"]; ?>" alt="Service Image">
            </div>
        <?php endwhile; ?>
        <div class="clearfix"></div>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>
