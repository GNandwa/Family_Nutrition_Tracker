<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Family Nutrition Tracker and Planner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            background-image: url('fntpsbackground.jpg');
            background-size: cover; 
            background-repeat: no-repeat;
        }
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1rem;
            width: 100%;
            position: absolute;
            top: 0;
        }
        .content {
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            cursor: pointer;
            border-radius: 5px;
            margin: 1rem;
            font-size: 1rem;
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
        <h1>Family Nutrition Tracker and Planner</h1>
    </header>
    <div class="content">
        <h2>Welcome to Our Family Nutrition Tracker!</h2>
        <p>
            Discover a healthier lifestyle with our comprehensive Family Nutrition Tracker and Planner. 
            Designed to empower families, our platform offers a user-friendly interface that simplifies meal planning, 
            family member preferences, and dietary management. Whether you're looking to balance nutrition, or manage your budget, 
            our system provides tailored meal plans, shoppign lists, and meals to meet your family's unique needs.
        </p>
        <p>
            With integrated tools for tracking meal recommendations and monitoring dietary compliance, you can take control 
            of your family's nutrition like never before. Join us in fostering healthier eating habits and enhancing 
            family health outcomes, making nutrition planning both accessible and enjoyable!
        </p>
        <button class="button" onclick="showPopup('signupPopup')">Sign Up</button>
        <button class="button" onclick="showPopup('loginPopup')">Log In</button>
    </div>
    
    <!-- Sign Up Popup -->
    <div class="popup" id="signupPopup">
        <div class="popup-content">
            <h2>Sign Up</h2>
            <p>Please fill in your details to create an account.</p>
            <form action="signup.php" method="POST">
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit" class="button">Create Account</button>
            </form>
            <button class="close" onclick="closePopup('signupPopup')">Close</button>
        </div>
    </div>

    <!-- Log In Popup -->
    <div class="popup" id="loginPopup">
        <div class="popup-content">
            <h2>Log In</h2>
            <p>Please enter your credentials to log in.</p>
            <form action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit" class="button">Log In</button>
            </form>
            <button class="close" onclick="closePopup('loginPopup')">Close</button>
        </div>
    </div>

    <script>
        function showPopup(popupId) {
            document.getElementById(popupId).style.display = 'flex';
        }
    
        function closePopup(popupId) {
            document.getElementById(popupId).style.display = 'none';
        }           
    </script>

</body>
</html>
