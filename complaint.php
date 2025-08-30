<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    
    $photo = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // File upload path
        $targetDir = "uploads/";
        $fileName = basename($_FILES["photo"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($fileType, $allowedTypes)) {
            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $photo = $fileName;  // Store the filename in the database
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }


    $sql = "INSERT INTO complaints (title, photo, location, description) 
            VALUES ('$title', '$photo', '$location', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "Complaint submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

include('db.php');

// Get form data
$user_email = $_POST['email'];  // Assuming the form sends email
$complaint_title = $_POST['title'];
$complaint_description = $_POST['description'];

// SQL query to insert the complaint data
$sql = "INSERT INTO complaints (user_email, complaint_title, complaint_description)
        VALUES ('$user_email', '$complaint_title', '$complaint_description')";

if ($conn->query($sql) === TRUE) {
    // Send email to the user
    $subject = "Complaint Submitted Successfully";
    $message = "Dear User,\n\nThank you for submitting your complaint. We have received the following details:\n\n".
                "Complaint Title: $complaint_title\nDescription: $complaint_description\n\n" .
                "Your complaint is now under review, and you will be notified of any updates.\n\n" .
                "Thank you,\nScity Team";
    $headers = "From: no-reply@scity.com";

    if (mail($user_email, $subject, $message, $headers)) {
        echo "Complaint submitted successfully and email notification sent.";
    } else {
        echo "Complaint submitted, but email notification failed.";
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Submission</title>
    <link rel="stylesheet" href="complain.css">
</head>
<body>

    <div class="container">
        <h2>Submit a Complaint</h2>
        <form action="complain.php" method="POST" enctype="multipart/form-data">
            <label for="title">Problem Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="photo">Upload Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*">

            <label for="location">Problem Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <button type="submit">Submit Complaint</button>
        </form>

        <div id="message"></div>
    </div>

</body>
</html>
