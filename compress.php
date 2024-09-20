<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function compressImage($source, $destination, $quality)
{
    $imgInfo = getimagesize($source);
    if (!$imgInfo) {
        return false;
    }
    $mime = $imgInfo['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    imagejpeg($image, $destination, $quality);
    imagedestroy($image);

    return $destination;
}

function createZip($destination, $files = [])
{
    $zip = new ZipArchive();
    if ($zip->open($destination, ZipArchive::CREATE) !== TRUE) {
        return false;
    }

    foreach ($files as $file) {
        if (file_exists($file)) {
            $zip->addFile($file, basename($file));
        }
    }

    $zip->close();
    return $destination;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['images']) && is_array($_FILES['images']['tmp_name'])) {
        $uploadPath = 'uploads/';
        $compressedFiles = [];
        $token = bin2hex(random_bytes(16)); // Generate a unique token
        $uploadDir = $uploadPath . $token . '/';
        mkdir($uploadDir, 0777, true); // Create a unique directory for this upload

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $fileName = $_FILES['images']['name'][$key];
            $fileTmp = $_FILES['images']['tmp_name'][$key];
            $compressedFilePath = $uploadDir . 'compressed_' . $fileName;

            if (compressImage($fileTmp, $compressedFilePath, 60)) {
                $compressedFiles[] = $compressedFilePath;
            }
        }

        // Create a ZIP file
        $zipFilePath = $uploadPath . $token . '_compressed_images.zip';
        if (createZip($zipFilePath, $compressedFiles)) {
            // Clean up the compressed files
            foreach ($compressedFiles as $file) {
                unlink($file); // Remove compressed files
            }

            // Redirect to the result page with the token
            header("Location: result.php?token=" . urlencode($token));
            exit();
        } else {
            echo "Failed to create ZIP file.";
        }
    } else {
        echo "No files were uploaded.";
    }
}
