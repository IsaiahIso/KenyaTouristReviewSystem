<?php
include 'db.php';

// Check if museum ID is provided
if (!isset($_GET['id'])) {
    echo "Museum ID not specified.";
    exit;
}

$museum_id = intval($_GET['id']);

// Fetch museum details
$sql = "SELECT * FROM museums WHERE id = $museum_id";
$museum_result = mysqli_query($conn, $sql);

if (mysqli_num_rows($museum_result) == 0) {
    echo "Museum not found.";
    exit;
}

$museum = mysqli_fetch_assoc($museum_result);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['review_text']);

    if (!empty($comment) && $rating >= 1 && $rating <= 5) {
        $insert_sql = "INSERT INTO reviews (museum_id, rating, comment, created_at)
                       VALUES ($museum_id, $rating, '$comment', NOW())";
        mysqli_query($conn, $insert_sql);
    }

    // Redirect to prevent resubmission
    header("Location: museum_details.php?id=$museum_id");
    exit;
}

// Fetch reviews for this museum
$reviews_sql = "SELECT * FROM reviews WHERE museum_id = $museum_id ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);

// Calculate average rating
$avg_rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews 
                   FROM reviews WHERE museum_id = $museum_id";
$avg_result = mysqli_query($conn, $avg_rating_sql);
$avg_data = mysqli_fetch_assoc($avg_result);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $museum['name']; ?> - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<div class="attractions-details-container">

    <a href="museums.php">← Back to Museums</a>

    <h1><?php echo $museum['name']; ?></h1>

    <!-- Big Image -->
    <img class="attractions-image" src="<?php echo $museum['image']; ?>" alt="<?php echo $museum['name']; ?>">

    <!-- Description -->
    <p><?php echo $museum['description']; ?></p>

    <!-- Average Rating -->
    <div class="rating-box">
        <span class="stars">
            <?php 
            $rating = round($avg_data['avg_rating']);
            for($i=1; $i<=5; $i++){
                echo $i <= $rating ? "★" : "☆";
            }
            ?>
        </span>
        <span>(<?php echo $avg_data['total_reviews']; ?> reviews)</span>
    </div>

    <!-- Review Form -->
    <h2>Leave a Review</h2>
    <form action="museum_details.php?id=<?php echo $museum_id; ?>" method="POST">
        <label>Rating:</label>
        <select name="rating" class="rating-select" required>
            <option value="">Select</option>
            <option value="1">★</option>
            <option value="2">★★</option>
            <option value="3">★★★</option>
            <option value="4">★★★★</option>
            <option value="5">★★★★★</option>
        </select>
        <br>

        <label>Review:</label><br>
        <textarea name="review_text" rows="4" required></textarea><br>

        <button type="submit">Submit Review</button>
    </form>

    <!-- All Previous Reviews -->
    <h2>Reviews</h2>
    <div class="reviews-container">
        <?php if (mysqli_num_rows($reviews_result) > 0): ?>
            <?php while($review = mysqli_fetch_assoc($reviews_result)): ?>
                <div class="review-card">
                    <span class="stars">
                        <?php 
                        $r = intval($review['rating']);
                        for($i=1; $i<=5; $i++){
                            echo $i <= $r ? "★" : "☆";
                        }
                        ?>
                    </span>

                    <p><?php echo htmlspecialchars($review['comment']); ?></p>
                    <small>Posted on <?php echo $review['created_at']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews yet. Be the first to review!</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
