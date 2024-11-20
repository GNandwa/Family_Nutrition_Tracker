<?php
session_start();

include 'config.php';
include 'session_check.php';

// Fetch user profile information (example, adjust as needed)
$username = $_SESSION['username'];

// Fetch family members
/* $familyQuery = "SELECT * FROM family_members WHERE name = ?";
$stmt = $conn->prepare($familyQuery);
$stmt->bind_param("i", $_SESSION['name']); // Assuming user_id is stored in session
$stmt->execute();
$familyResult = $stmt->get_result(); */

// Query to fetch family members
$familyResult = $conn->query("SELECT * FROM family_members");

if (!$familyResult) {
    die("Database query failed: " . $conn->error);
}

// Fetch today's meal
$mealQuery = "SELECT * FROM meals ORDER BY RAND() LIMIT 1";
$mealResult = $conn->query($mealQuery);
$todayMeal = $mealResult->fetch_assoc();

// Fetch shopping items with quantity 0
$shoppingQuery = "SELECT * FROM shopping WHERE quantity = 0";
$shoppingResult = $conn->query($shoppingQuery);

// Fetch meal history
$historyQuery = "SELECT * FROM meal_history ORDER BY meal_date DESC LIMIT 5";
$historyResult = $conn->query($historyQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-color: #f4f4f4;
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
            padding: 2rem;
            flex: 1;
            gap: 2rem;
        }
        .dashboard-section {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 1rem;

            padding: 10px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin-left: 10px;
        }
        .button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h1>Family Nutrition Tracker & Planner</h1>
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
    <!-- User Profile Section -->
    <div class="dashboard-section">
        <h2>User Profile</h2>
        <p>Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>'s</p>
        
        <h3>Family Members</h3>
            <button class="button" onclick="openFamilyModal()">Add Family Member</button>
            <ul id="family-list">
                <?php if ($familyResult->num_rows > 0): ?>
                    <?php while ($member = $familyResult->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($member['name']); ?> - Preferences: <?php echo htmlspecialchars($member['meal_preferences']); ?>
                            <button class="button" onclick="editFamilyMember(<?php echo $member['id']; ?>)">Edit</button>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>No family members found.</li>
                <?php endif; ?>
            </ul>
    </div>

    <!-- Today's Meal Section -->
    <div class="dashboard-section">
        <h2>Today's Meal</h2>
        <?php if ($todayMeal): ?>
            <p><strong><?php echo htmlspecialchars($todayMeal['meal_name']); ?></strong></p>
            <p><?php echo htmlspecialchars($todayMeal['ingredients']); ?></p>
            <button class="button" onclick="saveMeal(<?php echo $todayMeal['id']; ?>)">Save to History</button>
        <?php else: ?>
            <p>No meal available.</p>
        <?php endif; ?>
        <button class="button" onclick="getRandomMeal()">Get Meal</button>
    </div>

    <!-- This Week's Shopping List Section -->
    <div class="dashboard-section">
        <h2>This Week's Shopping List</h2>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($shoppingResult->num_rows > 0) {
                    while ($item = $shoppingResult->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($item['item_name']) . "</td></tr>";
                    }
                } else {
                    echo "<tr><td>No items in the shopping list.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Meal History Section -->
    <div class="dashboard-section">
        <h2>Meal History</h2>
        <table>
            <thead>
                <tr>
                    <th>Meal Name</th>
                    <th>Date Saved</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($historyResult->num_rows > 0) {
                    while ($history = $historyResult->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($history['meal_name']) . "</td>
                                <td>" . htmlspecialchars($history['meal_date']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No meal history available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Adding/Editing Family Members -->
<div id="familyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeFamilyModal()">&times;</span>
        <h2 id="modalTitle">Add Family Member</h2>
        <form id="familyForm" action="add_family_member.php" method="POST">
            <input type="hidden" id="memberId" name="id" value="">
            <label for="memberName">Name:</label>
            <input type="text" id="memberName" name="name" required>
            <label for="mealPreferences">Meal Preferences:</label>
            <input type="text" id="mealPreferences" name="preferences" required>
            <button type="submit" class="button">Save</button>
        </form>
    </div>
</div>

<script>
    function logout() {
        alert("Logged out successfully!");
        window.location.href = 'logout.php'; // Adjust as needed
    }

    function getRandomMeal() {
        fetch('random_meal.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector('.dashboard-section h2 + p').innerHTML = `<strong>${data.meal_name}</strong><br>${data.ingredients}`;
                } else {
                    alert("Error fetching meal.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

    function saveMeal(mealId) {
        fetch('save_meal.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `meal_id=${mealId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Meal saved to history!");
                location.reload();
            } else {
                alert("Error saving meal: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    function openFamilyModal() {
        document.getElementById('familyModal').style.display = "block";
        document.getElementById('modalTitle').innerText = "Add Family Member";
        document.getElementById('familyForm').reset();
        document.getElementById('memberId').value = ""; // Reset hidden input
    }

    function closeFamilyModal() {
        document.getElementById('familyModal').style.display = "none";
    }

    document.getElementById('familyForm').onsubmit = function(event) {
        event.preventDefault();
        const memberId = document.getElementById('memberId').value;
        const memberName = document.getElementById('memberName').value;
        const mealPreferences = document.getElementById('mealPreferences').value;

        const url = memberId ? 'edit_family_member.php' : 'add_family_member.php';
        const data = new URLSearchParams();
        data.append('name', memberName);
        data.append('preferences', mealPreferences);
        if (memberId) data.append('id', memberId);

        fetch(url, {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Family member saved successfully!");
                location.reload(); // Reload to update the family list
            } else {
                alert("Error saving family member: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    };

    function editFamilyMember(memberId) {
        fetch('edit_family_member.php?id=' + memberId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('memberId').value = data.member.id;
                    document.getElementById('memberName').value = data.member.name;
                    document.getElementById('mealPreferences').value = data.member.preferences;
                    openFamilyModal();
                } else {
                    alert("Error fetching family member details.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }
</script>

</body>
</html>