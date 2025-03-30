<?php
session_start();

// Check if success message exists
$successMessage = $_SESSION['success'] ?? '';

// Unset the message to prevent repeated display
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Status</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .message { font-size: 20px; color: green; }
    </style>
</head>
<body>

<?php if (!empty($successMessage)): ?>
    <p class="message"><?php echo htmlspecialchars($successMessage); ?></p>
<?php else: ?>
    <p>No recent submissions.</p>
<?php endif; ?>

<a href="#">Go Back</a>

</body>
</html>
