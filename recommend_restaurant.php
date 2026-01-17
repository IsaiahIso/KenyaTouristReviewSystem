<?php
// recommend_restaurant.php
require_once 'auth_guard.php';
include 'db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $cuisine_type = mysqli_real_escape_string($conn, $_POST['cuisine_type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $why_recommend = mysqli_real_escape_string($conn, $_POST['why_recommend']);
    $price_range = mysqli_real_escape_string($conn, $_POST['price_range']);
    $opening_hours = mysqli_real_escape_string($conn, $_POST['opening_hours']);
    $submitter_name = mysqli_real_escape_string($conn, $_POST['submitter_name']);
    $submitter_email = mysqli_real_escape_string($conn, $_POST['submitter_email']);
    
    // Validate
    if (empty($name) || empty($location) || empty($description) || empty($why_recommend)) {
        $error = "Please fill in all required fields.";
    } else {
        // Insert into database
        $sql = "INSERT INTO recommended_restaurants 
                (name, location, cuisine_type, description, why_recommend, price_range, opening_hours, submitter_name, submitter_email) 
                VALUES 
                ('$name', '$location', '$cuisine_type', '$description', '$why_recommend', '$price_range', '$opening_hours', '$submitter_name', '$submitter_email')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Thank you! Your restaurant recommendation has been submitted for review.";
        } else {
            $error = "Error submitting recommendation. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recommend a Restaurant - KenyaTour</title>
    <link rel="stylesheet" href="recommend.css">
</head>
<body>

<div class="container">
    <!-- Back Link -->
    <a href="index.php" class="back-link">← Back to Home</a>
    
    <div class="recommend-form-container">
        <h1>Recommend a Restaurant</h1>
        <p class="subtitle">Found an amazing restaurant in Kenya? Share it with fellow travelers!</p>
        
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Form -->
        <form method="POST" action="" class="recommend-form">
            <div class="form-section">
                <h2>Restaurant Details</h2>
                
                <div class="form-group">
                    <label for="name">Restaurant Name *</label>
                    <input type="text" id="name" name="name" required 
                           placeholder="e.g., Mama''s Kitchen">
                </div>
                
                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" required 
                           placeholder="e.g., Uhuru Highway, Nairobi">
                </div>
                
                <div class="form-group">
                    <label for="cuisine_type">Cuisine Type</label>
                    <input type="text" id="cuisine_type" name="cuisine_type" 
                           placeholder="e.g., African, Italian, Seafood">
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" required 
                              placeholder="Describe the restaurant, ambiance, specialty dishes..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="why_recommend">Why do you recommend it? *</label>
                    <textarea id="why_recommend" name="why_recommend" rows="4" required 
                              placeholder="What makes this restaurant special?"></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Additional Information</h2>
                
                <div class="form-group">
                    <label for="price_range">Price Range</label>
                    <select id="price_range" name="price_range">
                        <option value="">Select price range...</option>
                        <option value="$">$ - Budget friendly</option>
                        <option value="$$">$$ - Moderate</option>
                        <option value="$$$">$$$ - Expensive</option>
                        <option value="$$$$">$$$$ - Fine dining</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="opening_hours">Opening Hours</label>
                    <input type="text" id="opening_hours" name="opening_hours" 
                           placeholder="e.g., 8 AM - 10 PM">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Your Information (Optional)</h2>
                
                <div class="form-group">
                    <label for="submitter_name">Your Name</label>
                    <input type="text" id="submitter_name" name="submitter_name" 
                           placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label for="submitter_email">Your Email</label>
                    <input type="email" id="submitter_email" name="submitter_email" 
                           placeholder="john@example.com">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="reset" class="btn-secondary">Clear Form</button>
                <button type="submit" class="btn-primary">Submit Recommendation</button>
            </div>
            
            <p class="form-footer">* Required fields. All submissions will be reviewed before being added to the site.</p>
        </form>
    </div>
</div>

</body>
</html>