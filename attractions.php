<?php
// attractions.php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Get attraction type from URL
$type = isset($_GET['type']) ? $_GET['type'] : 'park';

// Validate type
$valid_types = ['park', 'beach', 'museum', 'nature_trail'];
if (!in_array($type, $valid_types)) {
    $type = 'park';
}

// Type display names
$type_names = [
    'park' => 'NATIONAL PARKS',
    'beach' => 'BEACHES', 
    'museum' => 'MUSEUMS',
    'nature_trail' => 'NATURE TRAILS'
];

$page_title = $type_names[$type] ?? 'ATTRACTIONS';

// Fetch attractions with ratings
$sql = "SELECT a.*, 
        COALESCE((SELECT AVG(r.rating) FROM reviews r WHERE r.attraction_id = a.id), 0) AS avg_rating,
        COALESCE((SELECT COUNT(*) FROM reviews r WHERE r.attraction_id = a.id), 0) AS total_reviews
        FROM attractions a
        WHERE a.type = '$type'
        ORDER BY a.name ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - KenyaTour</title>
    <link rel="stylesheet" href="attractions.css">
</head>
<body>

<div class="container">
    <!-- Back to Home -->
    <a href="index.php" class="back-link">← Back to Home</a>
    
    <!-- Type Selector -->
    <div class="type-selector">
        <h3>Browse Attractions:</h3>
        <div class="type-buttons">
            <a href="attractions.php?type=park" class="type-btn <?php echo $type == 'park' ? 'active' : ''; ?>">
                National Parks
            </a>
            <a href="attractions.php?type=beach" class="type-btn <?php echo $type == 'beach' ? 'active' : ''; ?>">
                Beaches
            </a>
            <a href="attractions.php?type=museum" class="type-btn <?php echo $type == 'museum' ? 'active' : ''; ?>">
                Museums
            </a>
            <a href="attractions.php?type=nature_trail" class="type-btn <?php echo $type == 'nature_trail' ? 'active' : ''; ?>">
                Nature Trails
            </a>
        </div>
    </div>
    
    <!-- Page Title -->
    <h1 class="page-title"><?php echo $page_title; ?></h1>
    
    <!-- Attractions Grid -->
    <div class="attractions-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($attraction = mysqli_fetch_assoc($result)): ?>
                <div class="attraction-card">
                    <img src="<?php echo htmlspecialchars($attraction['image']); ?>" 
                         alt="<?php echo htmlspecialchars($attraction['name']); ?>">
                    
                    <div class="attraction-info">
                        <h3><?php echo htmlspecialchars($attraction['name']); ?></h3>
                        
                        <div class="location">
                            📍 <?php echo htmlspecialchars($attraction['location']); ?>
                        </div>
                        
                        <p class="description">
                            <?php echo htmlspecialchars(substr($attraction['description'], 0, 120)); ?>...
                        </p>
                        
                        <div class="rating">
                            <?php 
                            $stars = '';
                            $rating = round($attraction['avg_rating']);
                            for ($i = 1; $i <= 5; $i++) {
                                $stars .= $i <= $rating ? '★' : '☆';
                            }
                            ?>
                            <span class="stars"><?php echo $stars; ?></span>
                            <span class="review-count">(<?php echo $attraction['total_reviews']; ?> reviews)</span>
                        </div>
                        
                        <a href="attraction_details.php?id=<?php echo $attraction['id']; ?>" class="view-btn">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">
                <p>No attractions found in this category.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>