<?php
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "train_booking";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connected: " . $db_name;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];

    // check if the username is already in use
    $check_query = "SELECT * FROM user WHERE username = '$username'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        // duplicate username
        echo "Username is already taken. Please choose another.";
    } else {
        // insert user data
        $insert_query = "INSERT INTO users (username, password_hash, first_name, last_name, email) VALUES ('$username', '$password', '$first_name', '$last_name', '$email')";

        if ($conn->query($insert_query)) {
            header("Location: login.html");
            exit();
        } else {
            echo "Registration failed: " . $conn->error;
        }
    }
    $conn->close();
}
?>
