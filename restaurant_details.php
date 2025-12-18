<?php
// restaurant_details.php
include 'db.php';
include 'restaurant_functions.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: restaurants.php");
    exit;
}

$restaurant_id = $_GET['id'];
$restaurant = getRestaurantById($conn, $restaurant_id);

if (!$restaurant) {
    echo "<h2>Restaurant not found!</h2>";
    echo "<a href='restaurants.php'>Back to Restaurants</a>";
    exit;
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    
    if (addReview($conn, $restaurant_id, $rating, $comment, $name, $email)) {
        $success_message = "Thank you for your review!";
        // Refresh to show new review
        header("Location: restaurant_details.php?id=$restaurant_id");
        exit;
    } else {
        $error_message = "Failed to submit review. Please try again.";
    }
}

// Get all reviews for this restaurant
$reviews_result = getRestaurantReviews($conn, $restaurant_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($restaurant['name']); ?> - KenyaTour</title>
    <link rel="stylesheet" href="restaurants.css">
</head>
<body>

<div class="container">
    <!-- Back Link -->
    <a href="restaurants.php" class="back-link">← Back to Restaurants</a>
    
    <!-- Restaurant Header -->
    <div class="restaurant-header">
        <h1><?php echo htmlspecialchars($restaurant['name']); ?></h1>
        
        <!-- Location -->
        <div class="restaurant-location">
            📍 <?php echo htmlspecialchars($restaurant['location']); ?>
        </div>
        
        <!-- Overall Rating -->
        <div class="overall-rating">
            <?php echo formatRatingDisplay($restaurant['avg_rating'], $restaurant['total_reviews']); ?>
        </div>
    </div>
    
    <!-- Restaurant Details -->
    <div class="restaurant-details">
        <!-- Main Image -->
        <div class="restaurant-main-image">
            <img src="<?php echo htmlspecialchars($restaurant['image']); ?>" 
                 alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
        </div>
        
        <!-- Info Card -->
        <div class="info-card">
            <h3>Restaurant Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Cuisine:</strong>
                    <span><?php echo htmlspecialchars($restaurant['cuisine_type']); ?></span>
                </div>
                <div class="info-item">
                    <strong>Price Range:</strong>
                    <span><?php echo htmlspecialchars($restaurant['price_range']); ?></span>
                </div>
                <div class="info-item">
                    <strong>Opening Hours:</strong>
                    <span><?php echo htmlspecialchars($restaurant['opening_hours']); ?></span>
                </div>
                <?php if (!empty($restaurant['contact_phone'])): ?>
                <div class="info-item">
                    <strong>Contact:</strong>
                    <span><?php echo htmlspecialchars($restaurant['contact_phone']); ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($restaurant['website'])): ?>
                <div class="info-item">
                    <strong>Website:</strong>
                    <a href="<?php echo htmlspecialchars($restaurant['website']); ?>" target="_blank">
                        Visit Website
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Full Description -->
    <div class="full-description">
        <h3>About This Restaurant</h3>
        <p><?php echo nl2br(htmlspecialchars($restaurant['description'])); ?></p>
    </div>
    
    <!-- Review Form -->
    <div class="review-form-section">
        <h2>Leave a Review</h2>
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Your Rating:</label>
                <div class="star-rating">
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
                <textarea id="comment" name="comment" rows="5" required 
                          placeholder="Share your experience..."></textarea>
            </div>
            
            <button type="submit" name="submit_review" class="submit-review-btn">
                Submit Review
            </button>
        </form>
    </div>
    
    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2>Customer Reviews (<?php echo $restaurant['total_reviews']; ?>)</h2>
        
        <?php if (mysqli_num_rows($reviews_result) > 0): ?>
            <div class="reviews-list">
                <?php while ($review = mysqli_fetch_assoc($reviews_result)): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-rating">
                                <span class="stars">
                                    <?php 
                                    $rating = $review['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '★' : '☆';
                                    }
                                    ?>
                                </span>
                                <span class="rating-number"><?php echo $rating; ?>/5</span>
                            </div>
                            <div class="reviewer-info">
                                <?php if (!empty($review['reviewer_name'])): ?>
                                    <strong><?php echo htmlspecialchars($review['reviewer_name']); ?></strong>
                                <?php else: ?>
                                    <strong>Anonymous</strong>
                                <?php endif; ?>
                                <span class="review-date">
                                    <?php echo date('F j, Y', strtotime($review['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <div class="review-comment">
                            <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-reviews">
                <p>No reviews yet. Be the first to review this restaurant!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>