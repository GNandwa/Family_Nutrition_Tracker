<?php
session_start();

include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $preferences = $_POST['preferences'];

    $stmt = $conn->prepare("INSERT INTO family_members (name, meal_preferences) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $preferences);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
$conn->close();
?>
