<?php
// Database configuration
$servername = "localhost";  // Database host (usually localhost)
$username = "root";         // Database username (default for localhost: root)
$password = "";             // Database password (empty by default on localhost)
$dbname = "smart_city";     // The name of the database

// Create the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include the database connection
include('db.php');  // Make sure this file contains the database connection details

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form data
    $user_email = $_POST['email'];  // User's email
    $complaint_title = $_POST['title'];  // Complaint title
    $complaint_description = $_POST['description'];  // Complaint description

    // Sanitize and validate the data (basic sanitization)
    $user_email = filter_var($user_email, FILTER_SANITIZE_EMAIL);
    $complaint_title = htmlspecialchars($complaint_title);
    $complaint_description = htmlspecialchars($complaint_description);

    // Check if email is valid
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Prepare the SQL query to insert the complaint into the database
    $sql = "INSERT INTO complaints (user_email, complaint_title, complaint_description) 
            VALUES ('$user_email', '$complaint_title', '$complaint_description')";

    // Execute the query and check for errors
    if ($conn->query($sql) === TRUE) {
        // Success: Send a confirmation email to the user
        $subject = "Complaint Submitted Successfully";
        $message = "Dear User,\n\nThank you for submitting your complaint. We have received the following details:\n\n".
                    "Complaint Title: $complaint_title\nDescription: $complaint_description\n\n" .
                    "Your complaint is now under review, and you will be notified of any updates.\n\n" .
                    "Thank you,\nScity Team";
        $headers = "From: no-reply@scity.com";

        // Send the email
        if (mail($user_email, $subject, $message, $headers)) {
            echo "Complaint submitted successfully. An email notification has been sent to you.";
        } else {
            echo "Complaint submitted, but email notification failed.";
        }
    } else {
        // Error inserting data
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
