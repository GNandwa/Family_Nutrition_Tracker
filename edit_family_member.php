<?php
session_start();

include("config.php");

// Function to handle errors
function respond($success, $data = [], $error = null) {
    echo json_encode(array_merge(['success' => $success], $data, $error ? ['error' => $error] : []));
    exit;
}

// Check if the request method is POST or GET
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateFamilyMember($conn);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    getFamilyMember($conn, (int)$_GET['id']);
} else {
    respond(false, [], 'Invalid request method or parameters.');
}

// Function to update family member
function updateFamilyMember($conn) {
    // Validate input
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $preferences = filter_input(INPUT_POST, 'preferences', FILTER_SANITIZE_STRING);

    if ($id === false || $name === null || $preferences === null) {
        respond(false, [], 'Invalid input.');
    }

    $stmt = $conn->prepare("UPDATE family_members SET name = ?, meal_preferences = ? WHERE id = ?");
    
    if ($stmt === false) {
        respond(false, [], 'Database prepare error: ' . $conn->error);
    }

    $stmt->bind_param("ssi", $name, $preferences, $id);
    
    if ($stmt->execute()) {
        respond(true);
    } else {
        respond(false, [], $stmt->error);
    }

    $stmt->close();
}

// Function to get family member
function getFamilyMember($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM family_members WHERE id = ?");
    
    if ($stmt === false) {
        respond(false, [], 'Database prepare error: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $member = $result->fetch_assoc();
        respond(true, ['member' => $member]);
    } else {
        respond(false, [], 'Member not found.');
    }

    $stmt->close();
}

$conn->close();
?>