<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "mydb"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, now check the password
        $row = $result->fetch_assoc();
        
        // In a real-world scenario, you should use password_hash() and password_verify()
        if ($password == $row['password']) {
            // Redirect to a welcome page or dashboard after successful login
            header("Location: welcome.php");
        } else {
            // Incorrect password
            header("Location: index.php?error=Invalid password");
        }
    } else {
        // No user found
        header("Location: index.php?error=User not found");
    }

    $conn->close();
}
?>
