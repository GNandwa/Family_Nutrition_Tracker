<?php
session_start();
include("config.php");

$mealQuery = "SELECT * FROM meals ORDER BY RAND() LIMIT 1";
$mealResult = $conn->query($mealQuery);

if ($mealResult->num_rows > 0) {
    $meal = $mealResult->fetch_assoc();
    echo json_encode(["success" => true, "meal_name" => $meal['meal_name'], "ingredients" => $meal['ingredients']]);
} else {
    echo json_encode(["success" => false]);
}

$conn->close();
?>
