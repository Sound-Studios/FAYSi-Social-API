<?php
function uploadFile($file, $title) {
    $uploadDirectory = '/var/www/html/uploads/'; // Passe den Upload-Pfad entsprechend deiner Serverkonfiguration an
    $allowedExtensions = array('png', 'jpg', 'jpeg', 'webp', 'gif', 'mp4', 'mov', 'avi', 'mp3', 'wav');
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $maxFileSize = 10 * 1024 * 1024; // 10 MB
    if (!in_array($fileExt, $allowedExtensions)) {
        return "Invalid file type. Please only upload videos, audios, or images.";
    }
    if ($file['size'] > $maxFileSize) {
        return "File size exceeds the limit. Maximum file size is 10 MB.";
    }
    $fileName = uniqid() . '.' . $fileExt;
    $uploadPath = $uploadDirectory . $fileName;
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Erfolgreiche Antwort zurückgeben
        return "File uploaded successfully";
    } else {
        return "Failed to upload file.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $result = uploadFile($_FILES['file'], $title);
    echo json_encode(array("message" => $result));
    exit();
}

http_response_code(400);
echo json_encode(array("message" => "Invalid request"));
?>
