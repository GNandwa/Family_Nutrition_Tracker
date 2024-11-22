<?php
// Include database connection
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the meal ID from the request
    $meal_id = $_POST['meal_id'];

    // Fetch the meal details from the database
    $mealQuery = $conn->prepare("SELECT meal_name FROM meals WHERE id = ?");
    $mealQuery->bind_param("i", $meal_id);
    $mealQuery->execute();
    $mealResult = $mealQuery->get_result();

    if ($mealResult->num_rows > 0) {
        $meal = $mealResult->fetch_assoc();
        $meal_name = $meal['meal_name'];
        $meal_date = date('Y-m-d H:i:s'); // Current date and time

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO meal_history (meal_name, meal_date) VALUES (?, ?)");
        $stmt->bind_param("ss", $meal_name, $meal_date);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success response
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Meal not found."]);
    }

    $mealQuery->close();
}
$conn->close();
?>
