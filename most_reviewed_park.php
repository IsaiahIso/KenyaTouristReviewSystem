 <!-- Most Reviewed Parks Widget -->
    <h2>Most Reviewed Parks</h2>
    <div class="most-reviewed-container">
    <?php
    $most_reviewed_sql = "SELECT p.*, COUNT(r.id) AS review_count 
                          FROM national_parks p
                          LEFT JOIN reviews r ON p.id = r.park_id
                          GROUP BY p.id
                          ORDER BY review_count DESC
                          LIMIT 3";
    $most_reviewed_result = mysqli_query($conn, $most_reviewed_sql);

    while($most = mysqli_fetch_assoc($most_reviewed_result)) { ?>
        <div class="most-reviewed-card">
            <img src="<?php echo $most['image']; ?>" alt="<?php echo $most['name']; ?>">
            <h3><?php echo $most['name']; ?></h3>
            <span><?php echo $most['review_count']; ?> reviews</span>
        </div>
        
    <?php } ?>
    </div>