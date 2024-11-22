<?php
// add_item.php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO shopping (item_name, category, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $item_name, $category, $quantity); 

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "id" => $stmt->insert_id]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
}
?>
