<?php
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "";
$db_name = "train_booking";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$train_id = isset($_GET['train_id']) ? (int)$_GET['train_id'] : null;

if ($train_id === null) {
    die("Invalid or missing train_id.");
}

$query = "SELECT * FROM seats WHERE train_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $train_id);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $seatNumber = $row["seat_number"];
        $seatStatus = $row["status"];

        if ($seatStatus === 'available') {
            $seats[] = $seatNumber;
        }
    }
}

// Update seat status if seats are selected
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedSeats'])) {
    $selectedSeats = json_decode($_POST['selectedSeats']);
    if (is_array($selectedSeats) && !empty($selectedSeats)) {
        $updateQuery = "UPDATE seats SET status = 'booked' WHERE train_id = ? AND seat_number = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("is", $train_id, $selectedSeatNumber);

        foreach ($selectedSeats as $selectedSeatNumber) {
            $updateStmt->execute();
        }

        if ($updateStmt->affected_rows > 0) {
            $bookedSeats = implode(', ', $selectedSeats);
            echo "Seats booked successfully. You have booked the following seats: " . $bookedSeats;
        } 
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Train Seat Selection</title>
    <style>
        .seat {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: lightgray;
            margin: 5px;
            text-align: center;
            line-height: 40px;
            cursor: pointer;
        }
        .seat.booked {
            background-color: red;
            cursor: not-allowed;
        }
        .seat.selected {
            background-color: green;
        }
    </style>
</head>
<body>
    <h1>Train Seat Selection</h1>
    <p>Select a seat:</p>
    <div>
        <?php
        foreach ($seats as $seat) {
            echo "<div class='seat' data-seat-number='$seat'>$seat</div>";
        }
        ?>
    </div>
    <p>Selected Seat: <span id="selected-seat"></span></p>
    <a href="javascript:history.go(-1)">Back</a>

    <form id="booking-form" method="post">
        <input type="hidden" id="selected-seats" name="selectedSeats" value="[]">
        <button id="confirm-button" type="button">Confirm to Book</button>
    </form>

    <script>
        const seats = document.querySelectorAll('.seat');
        const selectedSeatElement = document.getElementById('selected-seat');
        const confirmButton = document.getElementById('confirm-button');
        const bookingForm = document.getElementById('booking-form');
        const selectedSeatsInput = document.getElementById('selected-seats');

        const selectedSeats = new Set();
            //add click event listener for each seat element
        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                if (!seat.classList.contains('booked')) {
                    seat.classList.toggle('selected');
                    const seatNumber = seat.getAttribute('data-seat-number');

                    if (seat.classList.contains('selected')) {
                        selectedSeats.add(seatNumber);
                    } else {
                        selectedSeats.delete(seatNumber);
                    }

                    selectedSeatElement.textContent = Array.from(selectedSeats).join(', ');
                    selectedSeatsInput.value = JSON.stringify(Array.from(selectedSeats));
                }
            });
        });

        // Join the selected seats details together
        confirmButton.addEventListener('click', () => {
            const selectedSeatNumbers = Array.from(selectedSeats);
            if (selectedSeatNumbers.length > 0) {
                console.log('Booking selected seats: ' + selectedSeatNumbers.join(', '));
                bookingForm.submit();
            }
        });
    </script>
</body>
</html>
