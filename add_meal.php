<?php
session_start();

include("config.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_name = $_POST['meal_name'];
    $meal_description = $_POST['meal_description'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO meals (meal_name, meal_description) VALUES (?, ?)");
    $stmt->bind_param("ss", $meal_name, $meal_description);

    if ($stmt->execute()) {
        header("Location: meals.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
