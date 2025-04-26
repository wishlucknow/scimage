<?php
// error_reporting(0); // Turn off all error reporting
// ini_set('display_errors', 0);

include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign user input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Check if file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define the directory where you want to store the uploaded images
        $uploadDir = "uploads/";

        // Generate a unique name for the image to avoid overwriting
        $imageName = uniqid() . '-' . basename($_FILES["image"]["name"]);
        
        // Path to store the image
        $uploadFile = $uploadDir . $imageName;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $uploadFile)) {
            // Insert the name, description, and image path into the database
            $sql = "INSERT INTO items (name, description, image) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            
            // Bind parameters: 'sss' means three string parameters
            mysqli_stmt_bind_param($stmt, 'sss', $name, $description, $uploadFile);
            
            // Execute the query
            $result = mysqli_stmt_execute($stmt);
            
            if ($result) {
                echo "Data inserted and image uploaded successfully.";
            } else {
                echo "Error inserting data: " . mysqli_error($conn);
            }
            
            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Failed to upload the image.";
        }
    } else {
        echo "No image uploaded or there was an error with the image upload.";
    }
}
?>
