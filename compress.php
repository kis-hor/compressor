<?php
if (isset($_FILES['image'])) {
    $uploadDir = 'uploads/';
    $imgFile = $_FILES['image']['tmp_name'];
    $fileName = basename($_FILES['image']['name']);
    $targetFile = $uploadDir . $fileName;

    // Compress image
    $quality = 75; // Set the quality of compression (0-100)
    $image = imagecreatefromjpeg($imgFile); // Create image resource from the uploaded file
    imagejpeg($image, $targetFile, $quality); // Compress and save the image

    // Get file sizes before and after compression
    $originalSize = filesize($imgFile);
    $compressedSize = filesize($targetFile);

    // Return JSON response
    echo json_encode([
        'originalSize' => $originalSize,
        'compressedSize' => $compressedSize,
        'filePath' => $targetFile
    ]);
}
