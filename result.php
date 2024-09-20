<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$token = isset($_GET['token']) ? $_GET['token'] : '';

if (empty($token)) {
    header("Location: index.php"); // Redirect to the form page if no token is provided
    exit();
}

$uploadPath = 'uploads/';
$zipFilePath = $uploadPath . $token . '_compressed_images.zip';

if (!file_exists($zipFilePath)) {
    header("Location: index.php"); // Redirect if the ZIP file does not exist
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Compressed Images</title>
</head>

<body>
    <h1>Images Compressed Successfully</h1>
    <p>Your images have been compressed and saved in a ZIP file.</p>
    <a href="<?php echo htmlspecialchars($zipFilePath); ?>" download>Download ZIP file</a>
    <a href="index.php">Back to Home</a>
</body>

</html>