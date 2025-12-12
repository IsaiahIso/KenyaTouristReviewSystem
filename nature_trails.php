<?php
include 'db.php';

// Fetch nature trails with average rating and total reviews
$sql = "SELECT n.*, 
        (SELECT AVG(rating) FROM reviews WHERE nature_trail_id = n.id) AS avg_rating,
        (SELECT COUNT(*) FROM reviews WHERE nature_trail_id = n.id) AS total_reviews
        FROM nature_trails n ORDER BY n.name ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nature Trails - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<h1 class="page-title">KENYA NATURE TRAILS</h1>

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

            <a class="view-btn" href="nature_trails_details.php?id=<?php echo $row['id']; ?>">View Details</a>
        </div>
    <?php } ?>
</div>

</body>
</html>
