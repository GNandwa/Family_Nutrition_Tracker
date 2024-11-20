<?php
session_start();

require_once("config.php");

function respond($success, $message = '') {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['name']) || empty($_POST['preferences'])) {
        respond(false, 'Name and preferences are required.');
    }

    $name = trim($_POST['name']);
    $preferences = trim($_POST['preferences']);

    $stmt = $conn->prepare("INSERT INTO family_members (name, meal_preferences) VALUES (?, ?)");
    
    if ($stmt === false) {
        respond(false, 'Database statement preparation failed: ' . $conn->error);
    }

    $stmt->bind_param("ss", $name, $preferences);

    if ($stmt->execute()) {
        respond(true);
    } else {
        respond(false, 'Database execution failed: ' . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>