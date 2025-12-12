<?php
include 'db.php';

// Fetch all museums
$sql = "SELECT * FROM museums ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Museums - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>
    <h1 class="page-title">KENYA MUSEUMS</h1>

<div class="attractions-container">
    <?php while($museum = mysqli_fetch_assoc($result)): ?>
        <div class="park-card">
            <img src="<?php echo $museum['image']; ?>" alt="<?php echo $museum['name']; ?>">
            <h3><?php echo $museum['name']; ?></h3>
            <p><?php echo substr($museum['description'], 0, 100) . "..."; ?></p>
            <a class="view-btn" href="museum_details.php?id=<?php echo $museum['id']; ?>">View Details</a>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
