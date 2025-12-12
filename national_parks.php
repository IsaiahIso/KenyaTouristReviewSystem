<?php
include 'db.php';

// Fetch parks with average rating and total reviews
$sql = "SELECT p.*, 
        (SELECT AVG(rating) FROM reviews WHERE park_id = p.id) AS avg_rating,
        (SELECT COUNT(*) FROM reviews WHERE park_id = p.id) AS total_reviews
        FROM national_parks p 
        ORDER BY p.name ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>National Parks - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<h1 class="page-title">KENYA NATIONAL PARKS</h1>

<div class="attractions-container">
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <div class="park-card">
            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">

            <h2><?php echo $row['name']; ?></h2>
            <p><?php echo substr($row['description'], 0, 120); ?>...</p>

            <!-- Average Rating -->
            <div class="rating-box">
                <span class="stars">
                    <?php 
                    $rating = round($row['avg_rating']);
                    for($i=1; $i<=5; $i++){
                        echo $i <= $rating ? "★" : "☆";
                    }
                    ?>
                </span>
                <span>(<?php echo $row['total_reviews']; ?> reviews)</span>
            </div>

            <!-- View Details Button -->
            <a class="view-btn" href="park_details.php?id=<?php echo $row['id']; ?>">View Details</a>
        </div>
    <?php } ?>
</div>

</body>
</html>
