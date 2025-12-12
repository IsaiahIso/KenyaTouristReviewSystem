<?php
include 'db.php';

// Check if beach ID is provided
if (!isset($_GET['id'])) {
    echo "Beach ID not specified.";
    exit;
}

$beach_id = intval($_GET['id']);

// Fetch beach details
$sql = "SELECT * FROM beaches WHERE id = $beach_id";
$beach_result = mysqli_query($conn, $sql);

if (mysqli_num_rows($beach_result) == 0) {
    echo "Beach not found.";
    exit;
}

$beach = mysqli_fetch_assoc($beach_result);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['review_text']);

    if (!empty($comment) && $rating >= 1 && $rating <= 5) {
        $insert_sql = "INSERT INTO reviews (beach_id, rating, comment, created_at)
                       VALUES ($beach_id, $rating, '$comment', NOW())";
        mysqli_query($conn, $insert_sql);
    }

    // Redirect to prevent resubmission
    header("Location: beach_details.php?id=$beach_id");
    exit;
}

// Fetch reviews for this beach
$reviews_sql = "SELECT * FROM reviews WHERE beach_id = $beach_id ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);

// Calculate average rating
$avg_rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews 
                   FROM reviews WHERE beach_id = $beach_id";
$avg_result = mysqli_query($conn, $avg_rating_sql);
$avg_data = mysqli_fetch_assoc($avg_result);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $beach['name']; ?> - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<div class="attractions-details-container">

    <a href="beaches.php">← Back to Beaches</a>

    <h1><?php echo $beach['name']; ?></h1>

    <!-- Big Image -->
    <img class="attractions-image" src="<?php echo $beach['image']; ?>" alt="<?php echo $beach['name']; ?>">

    <!-- Description -->
    <p><?php echo $beach['description']; ?></p>

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
    <form action="beach_details.php?id=<?php echo $beach_id; ?>" method="POST">
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
