<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya Tourist Review</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="auth.css"> <!-- Add auth.css -->
    
    <!-- JavaScript for Authentication -->
    <script src="auth.js" defer></script>
    <!-- 'defer' makes it execute after HTML is parsed -->
</head>
<body>

<!-- Header / Navigation -->
<header>
    <div class="logo">
        <img src="images/logo.png" alt="KenyaTour Logo" class="logo-img">
        KenyaTour
    </div>

    <nav>
        <ul class="menu">
            <li><a href="#home">Home</a></li>

             <!-- Update your Attractions dropdown -->
            <li class="dropdown">
               <a href="#">Attractions</a>
               <ul class="dropdown-menu">
                    <li><a href="attractions.php?type=park">National Parks</a></li>
                    <li><a href="attractions.php?type=beach">Beaches</a></li>
                    <li><a href="attractions.php?type=museum">Museums</a></li>
                   <li><a href="attractions.php?type=nature_trail">Nature Trails</a></li>
                </ul>
            </li>

             <li class="dropdown">
                <a href="#restaurants">Restaurants</a>
                <ul class="dropdown-menu">
                     <li><a href="restaurants.php?city=Nairobi">Nairobi Restaurants</a></li>
                     <li><a href="restaurants.php?city=Mombasa">Mombasa Restaurants</a></li>
                     <li><a href="traditional_food.php">Traditional Food Spots</a></li>
                 </ul>
            </li>

            <li class="dropdown">
                <a href="#recommend">Recommend</a>
                <ul class="dropdown-menu">
                    <li><a href="recommend_attraction.php">Recommend Attraction</a></li>
                    <li><a href="recommend_restaurant.php">Recommend Restaurant</a></li>
                    <li><a href="hidden_gems.php">Hidden Gems</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#report">Report</a>
                <ul class="dropdown-menu">
                    <li><a href="report_service.php">Report Bad Service</a></li>
                    <li><a href="report_scam.php">Report Scam</a></li>
                    <li><a href="report_safety.php">Report Safety Issue</a></li>
                </ul>
            </li>

             <!-- Change this in your index.php navigation -->
            <li><a href="#" onclick="openAuthModal(); return false;">Login</a></li>
        </ul>
    </nav>
</header>

<!-- Hero Section -->
<section id="home" class="hero">

    <div class="search-container">
        <h2>Find Attractions, Restaurants, Hotels & More</h2>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search for places, parks, hotels...">
            <button onclick="performSearch()">Search</button>
        </div>

        <div id="searchResults" class="results-box"></div>
    </div>

    <h1>Explore the Best of Kenya</h1>
    <p>Discover top tourist attractions, restaurants, and hidden gems across Kenya.</p>
     <!-- Change this in your hero section -->
    <a href="#" class="btn" onclick="openAuthModal(); switchToSignup(); return false;">Get Started</a>
</section>

<!-- Attractions Preview -->
<section id="attractions" class="attractions">
    <h2>Popular Tourist Attractions</h2>
    <div class="cards">
        <div class="card">
            <img src="images/maasai-mara.jpeg" alt="Maasai Mara">
            <h3>Maasai Mara</h3>
            <p>Experience the Great Migration and Kenya's wildlife in its natural habitat.</p>
        </div>
        <div class="card">
            <img src="images/amboseli1.jpeg" alt="Amboseli">
            <h3>Amboseli National Park</h3>
            <p>See majestic elephants with a stunning backdrop of Mount Kilimanjaro.</p>
        </div>
        <div class="card">
            <img src="images/diani.jpeg" alt="Diani Beach">
            <h3>Diani Beach</h3>
            <p>Relax on the pristine white sand beaches and turquoise waters of the Indian Ocean.</p>
        </div>
    </div>
</section>

  <!-- Most Reviewed Attractions Section -->
<section id="most-reviewed" class="most-reviewed">
    <div class="section-header">
        <h2>🔥 Most Reviewed Attractions</h2>
        <p>Discover what fellow travelers are raving about</p>
    </div>
    
    <div class="cards">
        <!-- This content will be populated by PHP -->
        <?php
        // PHP code to fetch most reviewed attractions from database
        // Example structure:
        /*
        require_once 'config/database.php';
        
        try {
            $stmt = $pdo->query("
                SELECT a.*, COUNT(r.id) as review_count, AVG(r.rating) as avg_rating
                FROM attractions a 
                LEFT JOIN reviews r ON a.id = r.attraction_id 
                GROUP BY a.id 
                ORDER BY review_count DESC, avg_rating DESC 
                LIMIT 3
            ");
            
            while($attraction = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="card">';
                echo '<img src="' . htmlspecialchars($attraction['image_url']) . '" alt="' . htmlspecialchars($attraction['name']) . '">';
                echo '<div class="card-badge">' . $attraction['review_count'] . ' reviews</div>';
                echo '<h3>' . htmlspecialchars($attraction['name']) . '</h3>';
                echo '<div class="rating">';
                // Display star rating
                $rating = round($attraction['avg_rating'], 1);
                for($i = 1; $i <= 5; $i++) {
                    if($i <= floor($rating)) {
                        echo '★';
                    } elseif($i == ceil($rating) && fmod($rating, 1) >= 0.5) {
                        echo '½';
                    } else {
                        echo '☆';
                    }
                }
                echo '<span class="rating-text">' . $rating . '/5</span>';
                echo '</div>';
                echo '<p>' . htmlspecialchars(substr($attraction['description'], 0, 100)) . '...</p>';
                echo '<a href="attraction_details.php?id=' . $attraction['id'] . '" class="btn-small">View Details</a>';
                echo '</div>';
            }
        } catch(PDOException $e) {
            echo '<p>Unable to load attractions at this time.</p>';
        }
        */
        ?>
        
        <!-- Fallback static content if PHP not ready -->
        <div class="card">
            <img src="images/maasai-mara.jpeg" alt="Maasai Mara">
            <div class="card-badge">128 reviews</div>
            <h3>Maasai Mara</h3>
            <div class="rating">
                ★★★★★ <span class="rating-text">4.8/5</span>
            </div>
            <p>Top-rated for wildlife viewing and safari experiences.</p>
            <a href="attraction_details.php?id=1" class="btn-small">View Details</a>
        </div>
        <div class="card">
            <img src="images/diani.jpeg" alt="Diani Beach">
            <div class="card-badge">96 reviews</div>
            <h3>Diani Beach</h3>
            <div class="rating">
                ★★★★☆ <span class="rating-text">4.3/5</span>
            </div>
            <p>Famous for its white sand beaches and water sports.</p>
            <a href="attraction_details.php?id=3" class="btn-small">View Details</a>
        </div>
        <div class="card">
            <img src="images/nairobi-national-park.jpeg" alt="Nairobi National Park">
            <div class="card-badge">84 reviews</div>
            <h3>Nairobi National Park</h3>
            <div class="rating">
                ★★★★☆ <span class="rating-text">4.2/5</span>
            </div>
            <p>Unique wildlife park within the city limits.</p>
            <a href="attraction_details.php?id=4" class="btn-small">View Details</a>
        </div>
    </div>
</section>

<!-- Most Reviewed Restaurants Section -->
<section id="top-restaurants" class="top-restaurants">
    <div class="section-header">
        <h2>🍽️ Top Rated Restaurants</h2>
        <p>Where foodies are dining in Kenya</p>
    </div>
    
    <div class="cards">
        <!-- PHP code for restaurants -->
        <?php
        /*
        try {
            $stmt = $pdo->query("
                SELECT r.*, COUNT(rev.id) as review_count, AVG(rev.rating) as avg_rating
                FROM restaurants r 
                LEFT JOIN reviews rev ON r.id = rev.restaurant_id 
                GROUP BY r.id 
                ORDER BY review_count DESC, avg_rating DESC 
                LIMIT 3
            ");
            
            while($restaurant = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Similar card structure as attractions
            }
        } catch(PDOException $e) {
            // Fallback to static content
        }
        */
        ?>
        
        <!-- Fallback static content -->
        <div class="card">
            <img src="images/carnivore.jpg" alt="Carnivore Restaurant">
            <div class="card-badge">215 reviews</div>
            <h3>Carnivore Restaurant</h3>
            <div class="rating">
                ★★★★★ <span class="rating-text">4.7/5</span>
            </div>
            <p>Famous for its meat-centric menu and "beast of a feast".</p>
            <a href="restaurant_details.php?id=1" class="btn-small">View Details</a>
        </div>
        <div class="card">
            <img src="images/tamarind.jpg" alt="Tamarind Restaurant">
            <div class="card-badge">189 reviews</div>
            <h3>Tamarind Restaurant</h3>
            <div class="rating">
                ★★★★☆ <span class="rating-text">4.5/5</span>
            </div>
            <p>Upscale seafood with stunning views in Mombasa.</p>
            <a href="restaurant_details.php?id=2" class="btn-small">View Details</a>
        </div>
        <div class="card">
            <img src="images/java-house.jpeg" alt="Java House">
            <div class="card-badge">342 reviews</div>
            <h3>Java House</h3>
            <div class="rating">
                ★★★★☆ <span class="rating-text">4.3/5</span>
            </div>
            <p>Popular coffee chain with great breakfast options.</p>
            <a href="restaurant_details.php?id=3" class="btn-small">View Details</a>
        </div>
    </div>
</section>

<!-- Recommended For You Section (Personalized) -->
<section id="recommended" class="recommended">
    <div class="section-header">
        <h2>💫 Recommended For You</h2>
        <p>Personalized picks based on your interests</p>
    </div>
    
    <div class="tabs">
        <button class="tab-btn active" onclick="switchRecommendationTab('attractions')">Attractions</button>
        <button class="tab-btn" onclick="switchRecommendationTab('restaurants')">Restaurants</button>
    </div>
    
    <!-- Recommended Attractions Tab -->
    <div id="recommended-attractions" class="tab-content active">
        <div class="cards">
            <div class="card">
                <img src="images/hells-gate.jpeg" alt="Hell's Gate National Park">
                <div class="card-badge">Recommended</div>
                <h3>Hell's Gate National Park</h3>
                <p>Perfect for hiking and cycling enthusiasts. No predators make it safe for walking.</p>
                <a href="attraction_details.php?id=5" class="btn-small">Explore</a>
            </div>
             <div class="card">
                <img src="images/fort-jesus.jpeg" alt="Fort Jesus">
                <div class="card-badge">Historical</div>
                <h3>Fort Jesus</h3>
                <p>16th-century Portuguese fort, a UNESCO World Heritage site in Mombasa.</p>
                <a href="attraction_details.php?id=25" class="btn-small">Explore</a>
            </div>
            <div class="card">
                <img src="images/tsavo-east.jpeg" alt="Tsavo East">
                <div class="card-badge">Adventure</div>
                <h3>Tsavo East National Park</h3>
                <p>One of Kenya's largest parks, home to the famous "red elephants".</p>
                <a href="attraction_details.php?id=7" class="btn-small">Explore</a>
            </div>
        </div>
    </div>
    
    <!-- Recommended Restaurants Tab -->
    <div id="recommended-restaurants" class="tab-content">
        <div class="cards">
            <div class="card">
                <img src="images/talisman.jpg" alt="The Talisman">
                <div class="card-badge">Local Favorite</div>
                <h3>The Talisman</h3>
                <p>Eclectic menu in a beautiful garden setting in Karen, Nairobi.</p>
                <a href="restaurant_details.php?id=4" class="btn-small">View Menu</a>
            </div>
            <div class="card">
                <img src="images/artcaffe.jpg" alt="Artcaffe">
                <div class="card-badge">Trending</div>
                <h3>Artcaffe</h3>
                <p>Modern cafe with artisanal coffee, bakery, and bistro menu.</p>
                <a href="restaurant_details.php?id=5" class="btn-small">View Menu</a>
            </div>
            <div class="card">
                <img src="images/habesha.jpg" alt="Habesha Restaurant">
                <div class="card-badge">Hidden Gem</div>
                <h3>Habesha Ethiopian Restaurant</h3>
                <p>Authentic Ethiopian cuisine with traditional coffee ceremony.</p>
                <a href="restaurant_details.php?id=6" class="btn-small">View Menu</a>
            </div>
        </div>
    </div>
    
    <div class="section-footer">
        <a href="recommend_attraction.php" class="btn-secondary">Get More Attraction Recommendations</a>
        <a href="recommend_restaurant.php" class="btn-secondary">Get Restaurant Recommendations</a>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2025 LJ-CR7 KenyaTour. All rights reserved.</p>
</footer>

<!-- Authentication Modal -->
<div id="authModal" class="auth-modal">
    <div class="auth-modal-content">
        <!-- Close Button -->
        <span class="close-modal" onclick="closeAuthModal()">&times;</span>
        
        <!-- Tabs for Login/Signup -->
        <div class="auth-tabs">
            <button class="tab-btn active" onclick="openTab(event, 'loginTab')">Sign In</button>
            <button class="tab-btn" onclick="openTab(event, 'signupTab')">Sign Up</button>
        </div>
        
        <!-- Login Form -->
        <div id="loginTab" class="tab-content active">
            <h2>Welcome Back!</h2>
            <p class="auth-subtitle">Sign in to your KenyaTour account</p>
            
            <form id="loginForm" class="auth-form" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="loginUsername">Email or Username</label>
                    <input type="text" id="loginUsername" name="username" required 
                           placeholder="Enter your email or username">
                </div>
                
                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="password" required 
                           placeholder="Enter your password">
                    <div class="password-toggle">
                        <input type="checkbox" id="showLoginPassword" onclick="togglePassword('loginPassword', this)">
                        <label for="showLoginPassword">Show Password</label>
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="auth-btn">Sign In</button>
                
                <div class="auth-divider">
                    <span>or continue with</span>
                </div>
                
                <div class="social-auth">
                    <button type="button" class="social-btn google-btn">
                        <img src="images/google-icon.png" alt="Google"> Google
                    </button>
                    <button type="button" class="social-btn facebook-btn">
                        <img src="images/facebook-icon.png" alt="Facebook"> Facebook
                    </button>
                </div>
            </form>
            
            <p class="auth-switch">
                Don't have an account? <a href="#" onclick="switchToSignup()">Sign up here</a>
            </p>
        </div>
        
        <!-- Signup Form -->
        <div id="signupTab" class="tab-content">
            <h2>Join KenyaTour</h2>
            <p class="auth-subtitle">Create your free account to save favorites and write reviews</p>
            
            <form id="signupForm" class="auth-form" onsubmit="handleSignup(event)">
                <div class="form-group">
                    <label for="signupUsername">Username *</label>
                    <input type="text" id="signupUsername" name="username" required 
                           placeholder="Choose a username" minlength="3" maxlength="20">
                    <small class="hint">3-20 characters, letters and numbers only</small>
                </div>
                
                <div class="form-group">
                    <label for="signupEmail">Email Address *</label>
                    <input type="email" id="signupEmail" name="email" required 
                           placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="signupFullName">Full Name</label>
                    <input type="text" id="signupFullName" name="full_name" 
                           placeholder="Enter your full name">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="signupPassword">Password *</label>
                        <input type="password" id="signupPassword" name="password" required 
                               placeholder="Create a password" minlength="6">
                        <div class="password-strength">
                            <div class="strength-bar"></div>
                            <small>Password strength: <span id="passwordStrength">Weak</span></small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="signupConfirmPassword">Confirm Password *</label>
                        <input type="password" id="signupConfirmPassword" name="confirm_password" required 
                               placeholder="Confirm your password">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="signupLocation">Location (Optional)</label>
                    <input type="text" id="signupLocation" name="location" 
                           placeholder="e.g., Nairobi, Kenya">
                </div>
                
                <div class="form-group terms-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" required>
                        I agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy Policy</a> *
                    </label>
                </div>
                
                <div class="form-group terms-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="newsletter">
                        Send me travel tips, recommendations, and updates from KenyaTour
                    </label>
                </div>
                
                <button type="submit" class="auth-btn">Create Account</button>
                
                <p class="auth-switch">
                    Already have an account? <a href="#" onclick="switchToLogin()">Sign in here</a>
                </p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
