<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya Tourist Review</title>
    <link rel="stylesheet" href="styles.css">
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

            <li class="dropdown">
                <a href="#">Attractions</a>
                <ul class="dropdown-menu">
                    <li><a href="national_parks.php" target="_blank">National Parks</a></li>
                    <li><a href="beaches.php" target="_blank">Beaches</a></li>
                    <li><a href="museums.php" target="_blank">Museums</a></li>
                    <li><a href="nature_trails.php">Nature Trails</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="#restaurants">Restaurants</a>
                <ul class="dropdown-menu">
                    <li><a href="nairobi_restaurants.php">Nairobi Restaurants</a></li>
                    <li><a href="mombasa_restaurants.php">Mombasa Restaurants</a></li>
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

            <li><a href="#login">Login</a></li>
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
    <a href="#attractions" class="btn">Get Started</a>
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

<!-- Footer -->
<footer>
    <p>&copy; 2025 LJ-CR7 KenyaTour. All rights reserved.</p>
</footer>
</body>
</html>
