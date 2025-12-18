<?php
// check_session.php
session_start();
include 'db.php';

header('Content-Type: application/json');

$response = [
    'logged_in' => false,
    'user' => null
];

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $response['logged_in'] = true;
    $response['user'] = [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'role' => $_SESSION['role'] ?? 'user'
    ];
}

echo json_encode($response);
?>