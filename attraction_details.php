<?php
// attraction_details.php
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch attraction with ratings
$sql = "SELECT a.*, 
        COALESCE((SELECT AVG(r.rating) FROM reviews r WHERE r.attraction_id = a.id), 0) AS avg_rating,
        COALESCE((SELECT COUNT(*) FROM reviews r WHERE r.attraction_id = a.id), 0) AS total_reviews
        FROM attractions a 
        WHERE a.id = $id";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "<h2>Attraction not found!</h2>";
    echo "<a href='attractions.php'>Back to Attractions</a>";
    exit;
}

$attraction = mysqli_fetch_assoc($result);

// Type display names
$type_names = [
    'park' => 'National Park',
    'beach' => 'Beach',
    'museum' => 'Museum', 
    'nature_trail' => 'Nature Trail'
];

$type_name = $type_names[$attraction['type']] ?? 'Attraction';

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    
    if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
        $sql = "INSERT INTO reviews (attraction_id, rating, comment, reviewer_name, reviewer_email, created_at) 
                VALUES ($id, $rating, '$comment', '$name', '$email', NOW())";
        
        mysqli_query($conn, $sql);
        header("Location: attraction_details.php?id=$id");
        exit;
    }
}

// Fetch reviews
$reviews_sql = "SELECT * FROM reviews WHERE attraction_id = $id ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($attraction['name']); ?> - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<div class="container">
    <!-- Back Link -->
    <a href="attractions.php?type=<?php echo $attraction['type']; ?>" class="back-link">
        ← Back to <?php echo ucfirst($attraction['type']) . 's'; ?>
    </a>
    
    <div class="attraction-details">
        <!-- Header -->
        <div class="attraction-header">
            <h1><?php echo htmlspecialchars($attraction['name']); ?></h1>
            <div class="location-type">
                <span class="location">📍 <?php echo htmlspecialchars($attraction['location']); ?></span>
                <span class="type">🏷️ <?php echo $type_name; ?></span>
            </div>
            
            <div class="overall-rating">
                <?php 
                $stars = '';
                $rating = round($attraction['avg_rating']);
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= $i <= $rating ? '★' : '☆';
                }
                ?>
                <span class="stars"><?php echo $stars; ?></span>
                <span class="rating-text"><?php echo number_format($attraction['avg_rating'], 1); ?> (<?php echo $attraction['total_reviews']; ?> reviews)</span>
            </div>
        </div>
        
        <!-- Main Image -->
        <div class="main-image">
            <img src="<?php echo htmlspecialchars($attraction['image']); ?>" 
                 alt="<?php echo htmlspecialchars($attraction['name']); ?>">
        </div>
        
        <!-- Description -->
        <div class="description-section">
            <h2>About This <?php echo $type_name; ?></h2>
            <p><?php echo nl2br(htmlspecialchars($attraction['description'])); ?></p>
        </div>
        
        <!-- Review Form -->
        <div class="review-form">
            <h2>Leave a Review</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Your Rating:</label>
                    <div class="star-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                            <label for="star<?php echo $i; ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name">Your Name (optional):</label>
                    <input type="text" id="name" name="name" placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label for="email">Your Email (optional):</label>
                    <input type="email" id="email" name="email" placeholder="john@example.com">
                </div>
                
                <div class="form-group">
                    <label for="comment">Your Review:</label>
                    <textarea id="comment" name="comment" rows="5" required placeholder="Share your experience..."></textarea>
                </div>
                
                <button type="submit" name="submit_review" class="submit-btn">Submit Review</button>
            </form>
        </div>
        
        <!-- Reviews -->
        <div class="reviews-section">
            <h2>Visitor Reviews (<?php echo $attraction['total_reviews']; ?>)</h2>
            
            <?php if (mysqli_num_rows($reviews_result) > 0): ?>
                <div class="reviews-list">
                    <?php while ($review = mysqli_fetch_assoc($reviews_result)): ?>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-rating">
                                    <span class="stars">
                                        <?php 
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $review['rating'] ? '★' : '☆';
                                        }
                                        ?>
                                    </span>
                                    <span><?php echo $review['rating']; ?>/5</span>
                                </div>
                                <div class="reviewer">
                                    <?php if (!empty($review['reviewer_name'])): ?>
                                        <strong><?php echo htmlspecialchars($review['reviewer_name']); ?></strong>
                                    <?php else: ?>
                                        <strong>Anonymous</strong>
                                    <?php endif; ?>
                                    <span class="date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                                </div>
                            </div>
                            <p class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to review!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>