<?php
// recommend_attraction.php
include 'db.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $why_recommend = mysqli_real_escape_string($conn, $_POST['why_recommend']);
    $best_time = mysqli_real_escape_string($conn, $_POST['best_time']);
    $estimated_cost = mysqli_real_escape_string($conn, $_POST['estimated_cost']);
    $submitter_name = mysqli_real_escape_string($conn, $_POST['submitter_name']);
    $submitter_email = mysqli_real_escape_string($conn, $_POST['submitter_email']);
    
    // Validate required fields
    if (empty($name) || empty($location) || empty($description) || empty($why_recommend)) {
        $error = "Please fill in all required fields.";
    } else {
        // Insert into database
        $sql = "INSERT INTO recommended_attractions 
                (name, location, description, type, why_recommend, best_time_to_visit, estimated_cost, submitter_name, submitter_email) 
                VALUES 
                ('$name', '$location', '$description', '$type', '$why_recommend', '$best_time', '$estimated_cost', '$submitter_name', '$submitter_email')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Thank you! Your attraction recommendation has been submitted for review.";
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
    <title>Recommend an Attraction - KenyaTour</title>
    <link rel="stylesheet" href="recommend.css">
</head>
<body>

<div class="container">
    <!-- Back Link -->
    <a href="index.php" class="back-link">← Back to Home</a>
    
    <div class="recommend-form-container">
        <h1>Recommend an Attraction</h1>
        <p class="subtitle">Know a great place that should be on KenyaTour? Share it with us!</p>
        
        <!-- Success/Error Messages -->
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Recommendation Form -->
        <form method="POST" action="" class="recommend-form">
            <div class="form-section">
                <h2>Attraction Details</h2>
                
                <div class="form-group">
                    <label for="name">Attraction Name *</label>
                    <input type="text" id="name" name="name" required 
                           placeholder="e.g., Kakamega Forest Reserve">
                </div>
                
                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" required 
                           placeholder="e.g., Kakamega County, Western Kenya">
                </div>
                
                <div class="form-group">
                    <label for="type">Type of Attraction *</label>
                    <select id="type" name="type" required>
                        <option value="">Select type...</option>
                        <option value="park">National Park/Reserve</option>
                        <option value="beach">Beach</option>
                        <option value="museum">Museum/Cultural Site</option>
                        <option value="nature_trail">Nature Trail/Hiking Spot</option>
                        <option value="other">Other (Waterfall, Viewpoint, etc.)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" required 
                              placeholder="Describe this attraction..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="why_recommend">Why do you recommend it? *</label>
                    <textarea id="why_recommend" name="why_recommend" rows="4" required 
                              placeholder="What makes this place special?"></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Additional Information</h2>
                
                <div class="form-group">
                    <label for="best_time">Best Time to Visit</label>
                    <input type="text" id="best_time" name="best_time" 
                           placeholder="e.g., Dry season (June-October)">
                </div>
                
                <div class="form-group">
                    <label for="estimated_cost">Estimated Cost (per person)</label>
                    <input type="text" id="estimated_cost" name="estimated_cost" 
                           placeholder="e.g., KES 500-1000">
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
                
                <p class="form-note">Your information will only be used to contact you if we need more details.</p>
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