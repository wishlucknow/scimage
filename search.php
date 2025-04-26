<?php
error_reporting(0); // Turn off all error reporting
ini_set('display_errors', 0); 
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target = "uploads/query.jpg";
    move_uploaded_file($_FILES["search_image"]["tmp_name"], $target);

    // Run Python script and get result
    $result = shell_exec("C:\\Python312\\python.exe compare.py $target 2>&1");
    echo nl2br($result); // Display Python script output
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Matched Image</title>
</head>
<body>

<?php
// Assuming Python script output matches the image filename.
// The image path where Python script saves the matched image
$image_path = "matched_output.jpg";  

// Check if the matched image exists
if (file_exists($image_path)) {
    echo "<h3>Matched Image:</h3>";
    // Display the image with the correct path
    echo "<img src=\"$image_path\" alt=\"Matched Image\">";
} else {
    echo "<p>No image found.</p>";
}
?>

</body>
</html>
