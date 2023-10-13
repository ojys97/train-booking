<?php
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "train_booking";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$coaches = [
    "1" => 1,
    "2" => 2,
    "3" => 3,
    "4" => 4,
    "5" => 5,
    "6" => 6
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if a coach has been selected.
    if (isset($_POST["coach"])) {
        $selectedCoach = $_POST["coach"];

        // Check if the selected coach exists in the array.
        if (array_key_exists($selectedCoach, $coaches)) {
            // Redirect to the seat selection page with the selected train ID.
            $trainId = $coaches[$selectedCoach];
            header("Location: seats.php?train_id=$trainId");
            exit();
        } else {
            echo "Invalid coach selection.";
        }
    } else {
        echo "Please select a coach.";
    }
}
?>
