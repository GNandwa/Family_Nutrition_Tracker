<?php
session_start();

require_once 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meal_name = filter_input(INPUT_POST, 'meal_name', FILTER_SANITIZE_STRING);
    $meal_description = filter_input(INPUT_POST, 'meal_description', FILTER_SANITIZE_STRING);

    // Check if inputs are not empty
    if (!empty($meal_name) && !empty($meal_description)) {
        // Prepare and bind
        if ($stmt = $conn->prepare("INSERT INTO meals (meal_name, meal_description) VALUES (?, ?)")) {
            $stmt->bind_param("ss", $meal_name, $meal_description);

            // Execute the statement and check for success
            if ($stmt->execute()) {
                header("Location: meals.php");
                exit();
            } else {
                // Log the error for debugging (consider using a logging library)
                error_log("Database error: " . $stmt->error);
                echo "An error occurred while adding the meal. Please try again.";
            }

            $stmt->close();
        } else {
            // Log the error for debugging
            error_log("Prepare failed: " . $conn->error);
            echo "An error occurred while preparing the statement.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}

// Close the database connection
$conn->close();
?>