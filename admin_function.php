<?php
require_once('db.php');

?>

<script>


function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.style.backgroundColor = '#FFC0CB'; // Change the color to pink (you can customize the color)
        }

function exit() {
            window.location.href = 'index.php';
        }

function hotelSaveChanges(hotelBookingId) {
    var row2 = document.querySelector('tr[data-id="' + hotelBookingId + '"]');
    var checkInDate = row2.cells[4].textContent.trim(); // Updated index to match PHP file
    var checkOutDate = row2.cells[5].textContent.trim(); // Updated index to match PHP file
    var numOfPeople = row2.cells[6].textContent.trim(); // Updated index to match PHP file
    var paymentAmount = row2.cells[7].textContent.trim(); // Updated index to match PHP file

    // Perform AJAX request to save changes
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Reload the page after a successful update
                var successMessage = document.createElement("p");
                successMessage.innerHTML = "Successfully edited!";
                successMessage.style.color = "green";
                row2.appendChild(successMessage);
            } else {
                // Handle error here
                console.error("Error saving changes: " + xhr.statusText);
            }
        }
    };

    xhr.open("POST", "edit_hotel_booking.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Update the data sent in the request to match the PHP file
    xhr.send("hotel_save_changes=1&hotel_booking_ID=" + hotelBookingId + "&checkInDate=" + checkInDate + "&checkOutDate=" + checkOutDate + "&numOfPeople=" + numOfPeople + "&payment_amount=" + paymentAmount);
}


function ticketSaveChanges(flightTicketId) {
    var row1 = document.querySelector('tr[data-id="' + flightTicketId + '"]');
    var paymentAmount = row1.cells[1].textContent.trim(); // Updated index to match PHP file
    var numOfPeople = row1.cells[2].textContent.trim(); // Updated index to match PHP file

    // Perform AJAX request to save changes
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var successMessage = document.createElement("p");
            successMessage.innerHTML = "Successfully edited!";
            successMessage.style.color = "green";
            row1.appendChild(successMessage);
        }
    };
    xhr.open("POST", "edit_ticket.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Update the data sent in the request to match the PHP file
    xhr.send("flight_save_changes=1&flight_ticket_ID=" + flightTicketId + "&payment_amount=" + paymentAmount + "&numOfPeople=" + numOfPeople);
}

function tourSaveChanges(tourBookingId) {
    var row3 = document.querySelector('tr[data-id="' + tourBookingId + '"]');
    var bookingDate = row3.cells[1].textContent.trim();
    var paymentAmount = row3.cells[2].textContent.trim();
    var bookingQuantityPeople = row3.cells[3].textContent.trim();

    // Perform AJAX request to save changes
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var successMessage = document.createElement("p");
            successMessage.innerHTML = "Successfully edited!";
            successMessage.style.color = "green";
            row3.appendChild(successMessage);
        }
    };
    xhr.open("POST", "edit_tour_booking.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("tour_save_changes=1&tour_booking_ID=" + tourBookingId + "&booking_date=" + bookingDate + "&payment_amount=" + paymentAmount + "&booking_quantity_people=" + bookingQuantityPeople);
}


</script>