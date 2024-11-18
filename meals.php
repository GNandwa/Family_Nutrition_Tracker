<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit();
}

include("config.php");

// Fetch meals from the database
$mealsQuery = "SELECT * FROM meals";
$mealsResult = $conn->query($mealsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meals - Family Nutrition Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1rem;
            width: 100%;
        }
        .nav {
            display: flex;
            justify-content: space-between;
            background-color: #333;
            padding: 0.5rem;
        }
        .nav a {
            color: white;
            text-decoration: none;
            padding: 1rem;
            text-align: center;
        }
        .nav a:hover {
            background-color: #575757;
        }
        .logout {
            background-color: #f44336;
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 5px;
        }
        .logout:hover {
            background-color: #d32f2f;
        }
        .container {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            flex: 1;
            gap: 1rem;
        }
        .frame {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 1rem;
            background-color: #f9f9f9;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .button:hover {
            background-color: #45a049;
        }
        .popup {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            text-align: center;
        }
        .close {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .close:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

<header>
    <h1>Meals - Family Nutrition Tracker</h1>
</header>

<div class="nav">
    <div>
        <a href="dashboard.php">Home</a>
        <a href="meals.php">Meals</a>
        <a href="shopping.php">Shopping</a>
    </div>
    <button class="logout" onclick="logout()">Logout</button>
</div>

<div class="container">
    <div class="frame">
        <h2>Meal Items</h2>
        <table id="mealsTable">
            <thead>
                <tr>
                    <th>Meal Name</th>
                    <th>Ingredients</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($mealsResult->num_rows > 0) {
                    while ($meal = $mealsResult->fetch_assoc()) {
                        echo "<tr>
                            <td>{$meal['meal_name']}</td>
                            <td>{$meal['meal_description']}</td>
                            <td>
                                <button class='button' onclick='deleteMeal({$meal['id']}, this)'>Delete</button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No meals found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="button" onclick="showPopup('addMealPopup')">Add Meal</button>
    </div>
</div>

<!-- Add Meal Popup -->
<div class="popup" id="addMealPopup">
    <div class="popup-content">
        <h2>Add New Meal</h2>
        <form id="addMealForm" method="POST" action="add_meal.php">
            <input type="text" name="meal_name" placeholder="Meal Name" required><br><br>
            <input type="text" name="meal_description" placeholder="Ingredients (comma separated)" required><br><br>
            <button type="submit" class="button">Add Meal</button>
        </form>
        <button class="close" onclick="closePopup('addMealPopup')">Close</button>
    </div>
</div>

<script>
    function logout() {
        alert("Logged out successfully!");
        window.location.href = 'login.php'; // Adjust as needed
    }

    function showPopup(popupId) {
        document.getElementById(popupId).style.display = 'flex';
    }

    function closePopup(popupId) {
        document.getElementById(popupId).style.display = 'none';
    }

    function deleteMeal(mealId, button) {
        if (confirm("Are you sure you want to delete this meal?")) {
            fetch(`delete_meal.php?id=${mealId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok) {
                        const row = button.parentNode.parentNode;
                        row.parentNode.removeChild(row);
                    } else {
                        alert("Failed to delete meal.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        }
    }
</script>

</body>
</html>
