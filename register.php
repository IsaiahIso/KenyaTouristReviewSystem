<?php
// register.php
session_start();
include 'db.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
    $location = mysqli_real_escape_string($conn, $_POST['location'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($username)) $errors[] = 'Username is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($password)) $errors[] = 'Password is required';
    
    if (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = 'Username must be 3-50 characters';
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if (!empty($errors)) {
        $response['message'] = implode(', ', $errors);
        echo json_encode($response);
        exit;
    }
    
    // Check if username exists
    $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check_user) > 0) {
        $response['message'] = 'Username already taken';
        echo json_encode($response);
        exit;
    }
    
    // Check if email exists
    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        $response['message'] = 'Email already registered';
        echo json_encode($response);
        exit;
    }
    
    // For now, store password as plain text (CHANGE IN PRODUCTION!)
    // In production: $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $hashed_password = $password;
    
    // Insert user
    $sql = "INSERT INTO users (username, email, password, full_name, location, created_at) 
            VALUES ('$username', '$email', '$hashed_password', '$full_name', '$location', NOW())";
    
    if (mysqli_query($conn, $sql)) {
        $user_id = mysqli_insert_id($conn);
        
        // Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = 'user';
        $_SESSION['logged_in'] = true;
        
        $response['success'] = true;
        $response['message'] = 'Registration successful!';
        $response['user'] = [
            'id' => $user_id,
            'username' => $username,
            'email' => $email,
            'role' => 'user'
        ];
    } else {
        $response['message'] = 'Registration failed: ' . mysqli_error($conn);
    }
}

echo json_encode($response);
?>