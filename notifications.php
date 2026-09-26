<?php

session_start();

require_once "config/Database.php";
require_once "classes/Notification.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$pdo = $database->connect();

$notificationObj = new Notification($pdo);

$notifications = $notificationObj->getByUser($_SESSION['user_id']);

$notificationObj->markAllAsRead($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notifications - FixHub</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f7f7;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .notification-item {
            background: white;
            padding: 16px 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px #ddd;
            margin-bottom: 12px;
        }

        .notification-item .message {
            margin: 0 0 6px 0;
        }

        .notification-item .time {
            color: #888;
            font-size: 13px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #f97316;
            text-decoration: none;
            font-weight: bold;
        }

        .no-notifications {
            color: #888;
        }

    </style>

</head>

<body>

    <div class="container">

        <a href="index.php" class="back-link">← Back to Home</a>

        <h1>Notifications</h1>

        <?php if (empty($notifications)) { ?>

            <p class="no-notifications">You have no notifications yet.</p>

        <?php } else { ?>

            <?php foreach ($notifications as $item) { ?>

                <div class="notification-item">
                    <p class="message"><?php echo htmlspecialchars($item['message']); ?></p>
                    <span class="time"><?php echo htmlspecialchars($item['created_at']); ?></span>
                </div>

            <?php } ?>

        <?php } ?>

    </div>
</body>
</html>