<?php
// Assuming you have a database connection in db.php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve parameters from the AJAX request
    $tourDate = $_POST['tourDate'];
    $quantityPeople = $_POST['quantityPeople'];
    $groupType = $_POST['groupType'];

    // Validate and sanitize the input as needed

    // Retrieve matching tours from the database
    $sql = "SELECT * FROM tour WHERE tour_type = '$groupType'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate HTML for the tour rows
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['tour_description'] . '</td>';
            echo '<td>' . $row['tour_duration'] . '</td>';
            echo '<td>' . $row['tour_cost_per_person'] . '</td>';
            echo '<td>' . $row['tour_type'] . '</td>';
            echo '<td>' . $row['tour_places'] . '</td>';
            echo '<td><button class="book-tour-button" onclick="bookTour(' . $row['tour_ID'] . ', ' . $row['tour_cost_per_person'] . ')">Book Tour</button></td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6">No tours available for the selected criteria</td></tr>';
    }
} else {
    // Handle invalid request method if needed
    echo 'Invalid request method';
}
?>
