<?php
// login.php
session_start();
include 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        $response['message'] = 'Database error';
        echo json_encode($response);
        exit;
    }
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verify password (TEMPORARY - use plain text for testing)
        // In production, use: password_verify($password, $user['password'])
        if ($password === $user['password']) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            $response['success'] = true;
            $response['message'] = 'Login successful!';
            $response['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            
            // Redirect admin to dashboard
            // Handle redirect after login
if ($user['role'] === 'admin') {
    $response['redirect'] = 'admin_dashboard.php';
} elseif (isset($_SESSION['redirect_after_login'])) {
    $response['redirect'] = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']);
} else {
    $response['redirect'] = 'index.php';
}
        } else {
            $response['message'] = 'Invalid password';
        }
    } else {
        $response['message'] = 'User not found';
    }
}

echo json_encode($response);
?>