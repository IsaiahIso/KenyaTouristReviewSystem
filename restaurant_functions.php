<?php
// restaurant_functions.php

/**
 * Get all restaurants, optionally filtered by city
 */
function getRestaurants($conn, $city = '') {
    $sql = "SELECT r.*, 
            COALESCE((SELECT AVG(rv.rating) FROM reviews rv WHERE rv.restaurant_id = r.id), 0) AS avg_rating,
            COALESCE((SELECT COUNT(*) FROM reviews rv WHERE rv.restaurant_id = r.id), 0) AS total_reviews
            FROM restaurants r";
    
    if (!empty($city)) {
        $safe_city = mysqli_real_escape_string($conn, $city);
        $sql .= " WHERE r.location LIKE '%$safe_city%'";
    }
    
    $sql .= " ORDER BY r.name ASC";
    
    return mysqli_query($conn, $sql);
}

/**
 * Get restaurant by ID with rating stats
 */
function getRestaurantById($conn, $id) {
    $safe_id = intval($id);
    
    $sql = "SELECT r.*, 
            COALESCE((SELECT AVG(rv.rating) FROM reviews rv WHERE rv.restaurant_id = r.id), 0) AS avg_rating,
            COALESCE((SELECT COUNT(*) FROM reviews rv WHERE rv.restaurant_id = r.id), 0) AS total_reviews
            FROM restaurants r 
            WHERE r.id = $safe_id";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

/**
 * Display star ratings
 */
function displayStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $stars .= '★'; // Full star
        } elseif ($i == ceil($rating) && fmod($rating, 1) >= 0.5) {
            $stars .= '½'; // Half star (you can use other symbols)
        } else {
            $stars .= '☆'; // Empty star
        }
    }
    return $stars;
}

/**
 * Format rating display
 */
function formatRatingDisplay($rating, $total_reviews) {
    if ($total_reviews == 0) {
        return '<span class="no-rating">No reviews yet</span>';
    }
    
    $stars = displayStars($rating);
    $formatted_rating = number_format($rating, 1);
    
    return '<span class="stars">' . $stars . '</span> ' . 
           '<span class="rating-number">' . $formatted_rating . '</span> ' .
           '<span class="review-count">(' . $total_reviews . ' reviews)</span>';
}

/**
 * Add a new review
 */
function addReview($conn, $restaurant_id, $rating, $comment, $reviewer_name = '', $reviewer_email = '') {
    $restaurant_id = intval($restaurant_id);
    $rating = intval($rating);
    $comment = mysqli_real_escape_string($conn, trim($comment));
    $reviewer_name = mysqli_real_escape_string($conn, trim($reviewer_name));
    $reviewer_email = mysqli_real_escape_string($conn, trim($reviewer_email));
    
    // Validation
    if ($rating < 1 || $rating > 5) {
        return false;
    }
    
    if (empty($comment)) {
        return false;
    }
    
    $sql = "INSERT INTO reviews (restaurant_id, rating, comment, reviewer_name, reviewer_email, created_at) 
            VALUES ($restaurant_id, $rating, '$comment', '$reviewer_name', '$reviewer_email', NOW())";
    
    return mysqli_query($conn, $sql);
}

/**
 * Get reviews for a restaurant
 */
function getRestaurantReviews($conn, $restaurant_id) {
    $safe_id = intval($restaurant_id);
    $sql = "SELECT * FROM reviews 
            WHERE restaurant_id = $safe_id 
            ORDER BY created_at DESC";
    
    return mysqli_query($conn, $sql);
}

/**
 * Get all unique cities from restaurants
 */
function getAllCities($conn) {
    $sql = "SELECT DISTINCT location FROM restaurants ORDER BY location";
    $result = mysqli_query($conn, $sql);
    
    $cities = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $location = $row['location'];
            // Extract just the city name (first part before comma)
            $city_parts = explode(',', $location);
            $city_name = trim($city_parts[0]);
            
            if (!in_array($city_name, $cities)) {
                $cities[] = $city_name;
            }
        }
    }
    
    return $cities;
}
?>