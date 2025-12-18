<?php
// restaurants.php
include 'db.php';
include 'restaurant_functions.php';

// Get city from URL or default to empty (all restaurants)
$city = isset($_GET['city']) ? $_GET['city'] : '';

// Get all cities for dropdown
$all_cities = getAllCities($conn);

// Get restaurants (filtered by city if specified)
$result = getRestaurants($conn, $city);

$page_title = !empty($city) ? $city . " Restaurants" : "All Restaurants";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($page_title); ?> - KenyaTour</title>
    <link rel="stylesheet" href="restaurants.css">
</head>
<body>

<div class="container">
    <!-- Back to Home Link -->
    <a href="index.php" class="back-link">← Back to Home</a>
    
    <!-- Page Header -->
    <h1 class="page-title">
        <?php 
        if (!empty($city)) {
            echo strtoupper(htmlspecialchars($city)) . " RESTAURANTS";
        } else {
            echo "ALL RESTAURANTS IN KENYA";
        }
        ?>
    </h1>
    
    <!-- City Filter -->
    <div class="city-filter">
        <h3>Filter by City:</h3>
        <div class="city-buttons">
            <a href="restaurants.php" class="city-btn <?php echo empty($city) ? 'active' : ''; ?>">All Cities</a>
            <?php foreach ($all_cities as $city_name): ?>
                <a href="restaurants.php?city=<?php echo urlencode($city_name); ?>" 
                   class="city-btn <?php echo $city == $city_name ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($city_name); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Restaurants Grid -->
    <div class="restaurants-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="restaurant-card">
                    <!-- Restaurant Image -->
                    <div class="restaurant-image">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                             alt="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>
                    
                    <!-- Restaurant Info -->
                    <div class="restaurant-info">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        
                        <!-- Location -->
                        <div class="location">
                            📍 <?php echo htmlspecialchars($row['location']); ?>
                        </div>
                        
                        <!-- Cuisine & Price -->
                        <div class="details">
                            <span class="cuisine"><?php echo htmlspecialchars($row['cuisine_type']); ?></span>
                            <span class="price"><?php echo htmlspecialchars($row['price_range']); ?></span>
                            <span class="hours">🕒 <?php echo htmlspecialchars($row['opening_hours']); ?></span>
                        </div>
                        
                        <!-- Description -->
                        <p class="description">
                            <?php echo htmlspecialchars(substr($row['description'], 0, 150)); ?>...
                        </p>
                        
                        <!-- Rating -->
                        <div class="rating">
                            <?php echo formatRatingDisplay($row['avg_rating'], $row['total_reviews']); ?>
                        </div>
                        
                        <!-- View Details Button -->
                        <a href="restaurant_details.php?id=<?php echo $row['id']; ?>" class="view-details-btn">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">
                <p>No restaurants found<?php echo !empty($city) ? ' in ' . htmlspecialchars($city) : ''; ?>.</p>
                <a href="restaurants.php" class="btn">View All Restaurants</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>