<?php

session_start();

require_once "config/Database.php";
require_once "classes/Service.php";

$database = new Database();
$pdo = $database->connect();

$serviceObj = new Service($pdo);
$services = $serviceObj->getPopular(8);

$unreadCount = 0;

if (isset($_SESSION['user_id'])) {

    require_once "classes/Notification.php";

    $notificationObj = new Notification($pdo);
    $unreadCount = $notificationObj->getUnreadCount($_SESSION['user_id']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FixHub - Home Services</title>

    <link rel="stylesheet" href="assets/style.css">

    <style>

        .user-welcome {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            margin-right: 15px;
        }

        .logout-btn {
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
            padding: 8px 16px;
            border: 1px solid rgba(239, 68, 68, 0.4);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .service-card {
            cursor: pointer;
        }

        .notification-bell {
            position: relative;
            font-size: 20px;
            text-decoration: none;
            margin-right: 15px;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: white;
            border-left: 4px solid #f97316;
            border-radius: 8px;
            padding: 14px 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
            min-width: 260px;
            max-width: 320px;
            animation: toastIn .3s ease forwards;
        }

        .toast strong {
            display: block;
            font-size: 13px;
            color: #f97316;
            margin-bottom: 4px;
        }

        .toast p {
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        .toast.hide {
            animation: toastOut .3s ease forwards;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(40px);
            }
        }

    </style>

</head>

<body>

<div class="toast-container" id="toastContainer"></div>

<header class="navbar">

    <a href="#home" class="logo">
        Fix<span>Hub</span>
    </a>

    <nav>
        <a href="#home">Home</a>
        <a href="#services">Services</a>
        <a href="search_service.php">Search</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </nav>

    <div class="nav-buttons">

        <?php if (isset($_SESSION['user_id'])): ?>

            <a href="notifications.php" class="notification-bell">
                🔔
                <?php if ($unreadCount > 0): ?>
                    <span class="notification-badge"><?php echo $unreadCount; ?></span>
                <?php endif; ?>
            </a>

            <span class="user-welcome">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
            </span>

            <a href="logout.php" class="logout-btn">
                Logout
            </a>

        <?php else: ?>

            <a href="login.php" class="login-btn">
                Login
            </a>

            <a href="register.php" class="register-btn">
                Register
            </a>

        <?php endif; ?>

    </div>

</header>

<section class="hero" id="home">

    <div class="hero-content">

        <div class="hero-badge">
            <span></span>
            Trusted Home Services
        </div>

        <p class="small-title">
            YOUR HOME, OUR RESPONSIBILITY
        </p>

        <h1>
            Find Trusted
            <span>Home Services</span>
            Easily
        </h1>

        <p class="hero-text">
            FixHub connects you with trusted service providers
            for plumbing, electrical work, cleaning, maintenance
            and more.
        </p>

        <div class="hero-buttons">

            <a href="#services" class="primary-btn">
                Explore Services
                <span>→</span>
            </a>

            <a href="register.php" class="secondary-btn">
                Get Started
            </a>

        </div>

        <div class="hero-stats">

            <div>
                <strong>6+</strong>
                <span>Services</span>
            </div>

            <div>
                <strong>24/7</strong>
                <span>Support</span>
            </div>

            <div>
                <strong>100%</strong>
                <span>Easy Booking</span>
            </div>

        </div>

    </div>

    <div class="hero-visual">

        <div class="tool-orbit orbit-one"></div>
        <div class="tool-orbit orbit-two"></div>

        <div class="tool tool-1">
            <div class="tool-shape wrench">🔧</div>
        </div>

        <div class="tool tool-2">
            <div class="tool-shape hammer">🔨</div>
        </div>

        <div class="tool tool-3">
            <div class="tool-shape screwdriver">🪛</div>
        </div>

        <div class="tool tool-4">
            <div class="tool-shape bolt">⚡</div>
        </div>

        <div class="tool tool-5">
            <div class="tool-shape screw">🔩</div>
        </div>

        <div class="tool tool-6">
            <div class="tool-shape gear">⚙️</div>
        </div>

        <span class="particle particle-1"></span>
        <span class="particle particle-2"></span>
        <span class="particle particle-3"></span>
        <span class="particle particle-4"></span>
        <span class="particle particle-5"></span>
        <span class="particle particle-6"></span>

        <div class="toolbox-wrapper">

            <div class="toolbox-glow"></div>

            <div class="toolbox">

                <div class="toolbox-handle"></div>

                <div class="toolbox-top">
                    <div class="toolbox-lock"></div>
                </div>

                <div class="toolbox-body">

                    <div class="toolbox-line"></div>

                    <div class="toolbox-label">
                        FIX<span>HUB</span>
                    </div>

                </div>

            </div>

            <div class="toolbox-shadow"></div>

        </div>

        <div class="service-floating-card">

            <div class="service-card-icon">
                ✓
            </div>

            <div>
                <strong>Service Ready</strong>
                <small>Professional near you</small>
            </div>

        </div>

    </div>

</section>

<section class="services" id="services">

    <div class="section-title reveal">

        <p>WHAT WE OFFER</p>

        <h2>
            Popular <span>Services</span>
        </h2>

        <span>
            Professional services whenever you need them.
        </span>

    </div>

    <div class="service-grid">

        <?php if (count($services) > 0) { ?>

            <?php foreach ($services as $index => $item) { ?>

                <div
                    class="service-card reveal"
                    onclick="window.location.href='service_details.php?id=<?php echo $item['id']; ?>'"
                >

                    <div class="service-icon">
                        <?php echo htmlspecialchars($item['icon']); ?>
                    </div>

                    <div class="service-number">
                        <?php echo str_pad($index + 1, 2, "0", STR_PAD_LEFT); ?>
                    </div>

                    <h3>
                        <?php echo htmlspecialchars($item['name']); ?>
                    </h3>

                    <p>
                        <?php echo htmlspecialchars($item['description']); ?>
                    </p>

                    <a href="service_details.php?id=<?php echo $item['id']; ?>">
                        View Service <span>→</span>
                    </a>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p>
                No services available at the moment.
            </p>

        <?php } ?>

    </div>

</section>

<section class="about" id="about">

    <div class="about-content reveal">

        <p class="small-title">
            WHY FIXHUB?
        </p>

        <h2>
            One Platform.
            <span>Many Solutions.</span>
        </h2>

    </div>

    <div class="about-text reveal">

        <p>
            FixHub makes it easier to find trusted professionals,
            compare services and book the help you need.
        </p>

        <p>
            Whether it's a small repair or a complete home service,
            FixHub helps you get it done.
        </p>

    </div>

</section>

<section class="cta reveal">

    <div class="cta-decoration"></div>

    <p>READY WHEN YOU ARE</p>

    <h2>
        Need help with your home?
    </h2>

    <span>
        Create your account and find the right service provider.
    </span>

    <a href="register.php">
        Create Your Account
        <strong>→</strong>
    </a>

</section>

<footer id="contact">

    <div class="footer-content">

        <div>

            <a href="#home" class="logo">
                Fix<span>Hub</span>
            </a>

            <p>
                Making home services easier.
            </p>

        </div>

        <div class="footer-links">

            <a href="#home">Home</a>
            <a href="#services">Services</a>
            <a href="#about">About</a>

        </div>

    </div>

    <div class="footer-bottom">
        © 2026 FixHub. All rights reserved.
    </div>

</footer>

<script src="assets/main.js"></script>

<script>

<?php if (isset($_SESSION['user_id'])): ?>

const toastContainer = document.getElementById('toastContainer');
const shownIds = JSON.parse(sessionStorage.getItem('shownToastIds') || '[]');

function showToast(message) {

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<strong>New Notification</strong><p>${message}</p>`;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function checkNotifications() {

    fetch('get_new_notifications.php')
        .then(res => res.json())
        .then(data => {

            data.forEach(item => {

                if (!shownIds.includes(item.id)) {
                    showToast(item.message);
                    shownIds.push(item.id);
                }
            });

            sessionStorage.setItem('shownToastIds', JSON.stringify(shownIds));
        });
}

checkNotifications();
setInterval(checkNotifications, 10000);

<?php endif; ?>

</script>

</body>
</html>