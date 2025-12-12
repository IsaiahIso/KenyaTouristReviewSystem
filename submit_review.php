<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $park_id = intval($_POST['park_id']);
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['review_text']); // map form field to DB column

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        echo "Invalid input.";
        exit;
    }

    $sql = "INSERT INTO reviews (park_id, rating, comment, created_at) 
            VALUES ($park_id, $rating, '$comment', NOW())";

    if (mysqli_query($conn, $sql)) {
        header("Location: park_details.php?id=$park_id"); // redirect back to park
        exit;
    } else {
        echo "Error submitting review: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
