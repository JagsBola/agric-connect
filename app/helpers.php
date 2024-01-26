<?php 

function formatCustomDate($dateString) {
    // Create a DateTime object from the input date string
    $date = new DateTime($dateString);

    // Get the current date
    $currentDate = new DateTime('now');

    // Check if the date is today
    if ($date->format('Y-m-d') == $currentDate->format('Y-m-d')) {
        // Format for today
        $formattedDate = $date->format('h:i A, \T\o\d\a\y');
    } else {
        // Format for other days
        $formattedDate = $date->format('h:i A, D, j M Y');
    }

    return $formattedDate;
}