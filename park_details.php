<?php
include 'db.php';

// Check if a park ID is provided
if (!isset($_GET['id'])) {
    echo "Park ID not specified.";
    exit;
}

$park_id = intval($_GET['id']);

// Fetch park details
$sql = "SELECT * FROM national_parks WHERE id = $park_id";
$park_result = mysqli_query($conn, $sql);

if (mysqli_num_rows($park_result) == 0) {
    echo "Park not found.";
    exit;
}

$park = mysqli_fetch_assoc($park_result);

// Fetch reviews for this park
$reviews_sql = "SELECT * FROM reviews WHERE park_id = $park_id ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);

// Calculate average rating
$avg_rating_sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS total_reviews FROM reviews WHERE park_id = $park_id";
$avg_result = mysqli_query($conn, $avg_rating_sql);
$avg_data = mysqli_fetch_assoc($avg_result);
$park_name_encoded = urlencode($park['name']);
$map_url = "https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q=$park_name_encoded";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $park['name']; ?> - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<div class="attractions-details-container">

    <a href="national_parks.php">← Back to Parks</a>

    <h1><?php echo $park['name']; ?></h1>

    <!-- Big Image -->
    <img class="attractions-image" src="<?php echo $park['image']; ?>" alt="<?php echo $park['name']; ?>">

    <!-- Full Description -->
    <p><?php echo $park['description']; ?></p>

    <!-- Optional Map -->
    <?php if (!empty($park['map_embed'])): ?>
    <div class="map-container">
        <?php echo $park['map_embed']; ?>
    </div>
    <?php endif; ?>

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
    <form action="submit_review.php" method="POST">
        <input type="hidden" name="park_id" value="<?php echo $park_id; ?>">
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
</div> <!-- /.park-details-container -->

</body>
</html>
