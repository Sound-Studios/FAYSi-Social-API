<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Example</title>
</head>
<body>
    <h1>File Upload Example</h1>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <input type="text" name="title" placeholder="Optional: Title">
        <button type="submit">Upload File</button>
    </form>
    <div id="message"></div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            fetch('http://example.com/upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('message').textContent = data.message;
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>

<?php
// Hier befindet sich das PHP-Skript
function uploadFile($file, $title) {
    $uploadDirectory = '/var/www/html/social.faysi.de/uploads/';
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
function getFile($fileName) {
    $uploadDirectory = '/var/www/html/social.faysi.de/uploads/';
    $filePath = $uploadDirectory . $fileName;
    if (file_exists($filePath)) {
        // Datei auslesen und zurückgeben
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $fileName);
        readfile($filePath);
        exit();
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "File not found."));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $result = uploadFile($_FILES['file'], $title);
    echo json_encode(array("message" => $result));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['file'])) {
    $fileName = $_GET['file'];
    getFile($fileName);
}

http_response_code(400);
echo json_encode(array("message" => "Invalid request"));
?>
