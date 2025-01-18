<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Navigation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2; /* Light background for contrast */
        }

        nav {
            background-color: #4CAF50; /* Green background */
            padding: 10px 20px; /* Padding around the nav */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        }

        nav ul {
            list-style-type: none; /* Remove bullet points */
            padding: 0; /* Remove padding */
            margin: 0; /* Remove margin */
            display: flex; /* Flexbox for horizontal layout */
        }

        nav ul li {
            margin-right: 20px; /* Space between items */
        }

        nav ul li a {
            color: white; /* White text color */
            text-decoration: none; /* No underline */
            padding: 10px 15px; /* Padding for links */
            border-radius: 4px; /* Rounded corners */
            transition: background 0.3s, color 0.3s; /* Smooth transition */
        }

        nav ul li a:hover {
            background-color: #45a049; /* Darker green on hover */
            color: #fff; /* Ensure text is still white */
        }

        nav ul li a.active {
            background-color: #367e36; /* Highlight for the active page */
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="course_register2.php" class="<?= basename($_SERVER['PHP_SELF']) == 'course_register2.php' ? 'active' : '' ?>">Course Registration</a></li>
            <li><a href="add-drop.php" class="<?= basename($_SERVER['PHP_SELF']) == 'add-drop.php' ? 'active' : '' ?>">Add-Drop</a></li>
            <li><a href="registered_courses.php" class="<?= basename($_SERVER['PHP_SELF']) == 'registered-courses.php' ? 'active' : '' ?>">Registered Courses</a></li>
            <li><a href="spi-cpi.php" class="<?= basename($_SERVER['PHP_SELF']) == 'spi-cpi.php' ? 'active' : '' ?>">SPI/CPI</a></li>
            <li><a href="transcript.php" class="<?= basename($_SERVER['PHP_SELF']) == 'transcript.php' ? 'active' : '' ?>">Transcript</a></li>
        </ul>
    </nav>
</body>
</html>
