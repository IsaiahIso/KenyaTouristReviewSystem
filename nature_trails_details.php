<?php
include 'db.php';

// Check if nature trail ID is provided
if (!isset($_GET['id'])) {
    echo "Nature Trail ID not specified.";
    exit;
}

$trail_id = intval($_GET['id']);

// Fetch trail details
$sql = "SELECT * FROM nature_trails WHERE id = $trail_id";
$trail_result = mysqli_query($conn, $sql);

if (mysqli_num_rows($trail_result) == 0) {
    echo "Nature trail not found.";
    exit;
}

$trail = mysqli_fetch_assoc($trail_result);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    if (!empty($review_text) && $rating >= 1 && $rating <= 5) {
        $insert_sql = "INSERT INTO reviews (nature_trail_id, rating, comment, created_at) 
                       VALUES ($trail_id, $rating, '$review_text', NOW())";
        mysqli_query($conn, $insert_sql);
    }

    header("Location: nature_trails_details.php?id=" . $trail_id);
    exit;
}

// Fetch reviews for this trail
$reviews_sql = "SELECT * FROM reviews WHERE nature_trail_id = $trail_id ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);

// Calculate average rating
$avg_rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews 
                   FROM reviews WHERE nature_trail_id = $trail_id";
$avg_result = mysqli_query($conn, $avg_rating_sql);
$avg_data = mysqli_fetch_assoc($avg_result);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $trail['name']; ?> - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<div class="attractions-details-container">

    <a href="nature_trails.php">← Back to Nature Trails</a>

    <h1><?php echo $trail['name']; ?></h1>

    <!-- Big Image -->
    <img class="attractions-image" src="<?php echo $trail['image']; ?>" alt="<?php echo $trail['name']; ?>">

    <!-- Full Description -->
    <p><?php echo $trail['description']; ?></p>

    <!-- Average Rating -->
    <div class="rating-box">
        <span class="stars">
            <?php 
            $rating = round($avg_data['avg_rating']);
            for ($i=1; $i<=5; $i++){
                echo $i <= $rating ? "★" : "☆";
            }
            ?>
        </span>
        <span>(<?php echo $avg_data['total_reviews']; ?> reviews)</span>
    </div>

    <!-- Review Form -->
    <h2>Leave a Review</h2>
    <form action="nature_trails_details.php?id=<?php echo $trail_id; ?>" method="POST">
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
