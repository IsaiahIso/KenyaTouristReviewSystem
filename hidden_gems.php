<?php
// hidden_gems.php
require_once 'auth_guard.php';
include 'db.php';

// Fetch approved hidden gems to display
$gems_sql = "SELECT * FROM hidden_gems WHERE status = 'approved' ORDER BY created_at DESC";
$gems_result = mysqli_query($conn, $gems_sql);

// Handle form submission
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $why_hidden_gem = mysqli_real_escape_string($conn, $_POST['why_hidden_gem']);
    $directions = mysqli_real_escape_string($conn, $_POST['directions']);
    $safety_tips = mysqli_real_escape_string($conn, $_POST['safety_tips']);
    $submitter_name = mysqli_real_escape_string($conn, $_POST['submitter_name']);
    $submitter_email = mysqli_real_escape_string($conn, $_POST['submitter_email']);
    
    // Validate
    if (empty($name) || empty($location) || empty($description) || empty($why_hidden_gem)) {
        $error = "Please fill in all required fields.";
    } else {
        // Insert into database
        $sql = "INSERT INTO hidden_gems 
                (name, location, type, description, why_hidden_gem, directions, safety_tips, submitter_name, submitter_email) 
                VALUES 
                ('$name', '$location', '$type', '$description', '$why_hidden_gem', '$directions', '$safety_tips', '$submitter_name', '$submitter_email')";
        
        if (mysqli_query($conn, $sql)) {
            $message = "Thank you! Your hidden gem has been submitted for review.";
        } else {
            $error = "Error submitting. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hidden Gems - KenyaTour</title>
    <link rel="stylesheet" href="recommend.css">
</head>
<body>

<div class="container">
    <!-- Back Link -->
    <a href="index.php" class="back-link">← Back to Home</a>
    
    <div class="hidden-gems-container">
        <h1>Kenya's Hidden Gems</h1>
        <p class="subtitle">Discover and share off-the-beaten-path places in Kenya</p>
        
        <!-- Messages -->
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- Featured Hidden Gems -->
        <div class="gems-grid">
            <?php if (mysqli_num_rows($gems_result) > 0): ?>
                <?php while($gem = mysqli_fetch_assoc($gems_result)): ?>
                    <div class="gem-card">
                        <h3><?php echo htmlspecialchars($gem['name']); ?></h3>
                        <div class="gem-location">📍 <?php echo htmlspecialchars($gem['location']); ?></div>
                        <div class="gem-type">🏷️ <?php echo htmlspecialchars($gem['type']); ?></div>
                        
                        <div class="gem-description">
                            <p><?php echo htmlspecialchars(substr($gem['description'], 0, 200)); ?>...</p>
                        </div>
                        
                        <div class="gem-details">
                            <div class="detail-item">
                                <strong>Why it's hidden:</strong>
                                <p><?php echo htmlspecialchars(substr($gem['why_hidden_gem'], 0, 150)); ?>...</p>
                            </div>
                            
                            <?php if (!empty($gem['safety_tips'])): ?>
                            <div class="detail-item">
                                <strong>⚠️ Safety Tips:</strong>
                                <p><?php echo htmlspecialchars($gem['safety_tips']); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="gem-footer">
                            <small>Submitted by: <?php echo htmlspecialchars($gem['submitter_name'] ?? 'Anonymous'); ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-gems">
                    <p>No hidden gems have been shared yet. Be the first to share one!</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Share Hidden Gem Form -->
        <div class="share-gem-form">
            <h2>Share a Hidden Gem</h2>
            <p>Know a secret spot that most tourists don't know about? Share it here!</p>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name of the Place *</label>
                    <input type="text" id="name" name="name" required 
                           placeholder="e.g., Secret Waterfall in Aberdares">
                </div>
                
                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" required 
                           placeholder="e.g., Near Nyeri Town, Aberdare Ranges">
                </div>
                
                <div class="form-group">
                    <label for="type">Type of Place *</label>
                    <input type="text" id="type" name="type" required 
                           placeholder="e.g., Waterfall, Viewpoint, Forest, Beach">
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" required 
                              placeholder="Describe this hidden gem..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="why_hidden_gem">Why is this a hidden gem? *</label>
                    <textarea id="why_hidden_gem" name="why_hidden_gem" rows="4" required 
                              placeholder="What makes it special and why is it not well-known?"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="directions">How to get there</label>
                    <textarea id="directions" name="directions" rows="3" 
                              placeholder="Directions or landmarks to help others find it"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="safety_tips">Safety Tips/Important Notes</label>
                    <textarea id="safety_tips" name="safety_tips" rows="3" 
                              placeholder="Any safety concerns or things to know before visiting"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="submitter_name">Your Name (optional)</label>
                    <input type="text" id="submitter_name" name="submitter_name" 
                           placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label for="submitter_email">Your Email (optional)</label>
                    <input type="email" id="submitter_email" name="submitter_email" 
                           placeholder="john@example.com">
                </div>
                
                <button type="submit" class="btn-primary">Submit Hidden Gem</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>