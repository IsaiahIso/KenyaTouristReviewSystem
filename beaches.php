<?php
include 'db.php';

// Fetch beaches with average rating and total reviews
$sql = "SELECT b.*, 
        (SELECT AVG(rating) FROM reviews WHERE beach_id = b.id) AS avg_rating,
        (SELECT COUNT(*) FROM reviews WHERE beach_id = b.id) AS total_reviews
        FROM beaches b ORDER BY b.name ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Beaches - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<h1 class="page-title">KENYA BEACHES</h1>

<div class="attractions-container"> <!-- reuse same grid styling -->
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="park-card"> <!-- reuse same card styling -->
            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h2><?php echo $row['name']; ?></h2>

            <p><?php echo substr($row['description'], 0, 120); ?>...</p>

            <!-- Rating Stars -->
            <div class="rating-box">
                <span class="stars">
                    <?php 
                    $rating = round($row['avg_rating']);
                    for($i = 1; $i <= 5; $i++){
                        echo $i <= $rating ? "★" : "☆";
                    }
                    ?>
                </span>
                <span>(<?php echo $row['total_reviews']; ?> reviews)</span>
            </div>

            <a class="view-btn" href="beach_details.php?id=<?php echo $row['id']; ?>">View Details</a>
        </div>
    <?php } ?>
</div>

</body>
</html>
