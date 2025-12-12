<!-- Most Reviewed Beaches Widget -->
<h2>Most Reviewed Beaches</h2>
<div class="most-reviewed-container">
<?php
$most_reviewed_sql = "SELECT b.*, COUNT(r.id) AS review_count 
                      FROM beaches b
                      LEFT JOIN reviews r ON b.id = r.beach_id
                      GROUP BY b.id
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
