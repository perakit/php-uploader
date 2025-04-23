<?php
// Set the Access-Control-Allow-Origin header to allow requests from any origin.
// **WARNING: This is generally NOT recommended for production environments.**
// **It allows any website to make requests to your PHP script.**
// **For production, replace '*' with the specific origin(s) you want to allow (e.g., 'https://yourdomain.com').**
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Allow POST and OPTIONS requests
header("Access-Control-Allow-Headers: Content-Type"); //Allow Content-Type header
header("Content-Type: application/json"); //Set the content type to json.

// Handle preflight OPTIONS request (required for CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Respond with OK for OPTIONS requests
    exit;
}

// Receiving FormData/Blob (if you used the FormData method)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image'])) {
        $folder = $_POST['folder'];
        $file = $_FILES['image'];

        $uploadDir = "uploads/$folder/"; // Directory to store uploads (create this directory!)
        
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . '_' . basename($file['name']); // Generate a unique filename
        $filePath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            echo json_encode(['success' => true, 'message' => 'Image uploaded successfully', 'filePath' => $filePath]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to move uploaded file']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No image file received']);
    }
    exit;
}

// If it's not a POST request:
http_response_code(405); // Method Not Allowed
echo json_encode(['error' => 'Method not allowed']);
