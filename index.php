<?php
// Handle AJAX form submission to database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['message'])) {
    header('Content-Type: application/json');

    // Check if we are inside the WordPress environment on the live server
    // Path on live server: /var/www/html/new.cccinfotech.com/profile/shubham-das/index.php
    // WordPress wp-load.php is at: /var/www/html/new.cccinfotech.com/wp-load.php (3 levels up)
    $wp_load_path = dirname(dirname(dirname(__FILE__))) . '/wp-load.php';

    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (file_exists($wp_load_path)) {
        // --- 1. WORDPRESS LIVE ENVIRONMENT ---
        define('WP_USE_THEMES', false);
        require_once($wp_load_path);
        global $wpdb;

        // Sanitize using WordPress core functions
        $fullname = sanitize_text_field($fullname);
        $email = sanitize_email($email);
        $message = sanitize_textarea_field($message);

        // Validation constraints
        $errors = [];
        if (empty($fullname) || strlen($fullname) < 2) {
            $errors[] = 'Full name must be at least 2 characters.';
        }
        if (empty($email) || !is_email($email)) {
            $errors[] = 'Please provide a valid email address.';
        }
        if (empty($message) || strlen($message) < 10) {
            $errors[] = 'Message must be at least 10 characters.';
        }

        if (!empty($errors)) {
            echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
            exit;
        }

        // Target database table name
        $table_name = 'shubham_das_lead';
        $charset_collate = $wpdb->get_charset_collate();

        // Create table in the WordPress database if it doesn't exist
        $createTableSQL = "CREATE TABLE IF NOT EXISTS `$table_name` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `fullname` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `message` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($createTableSQL);

        // Insert lead entry via WordPress DB abstraction
        $inserted = $wpdb->insert(
            $table_name,
            [
                'fullname' => $fullname,
                'email'    => $email,
                'message'  => $message
            ],
            ['%s', '%s', '%s']
        );

        if ($inserted !== false) {
            echo json_encode(['status' => 'success', 'message' => 'Your message has been stored in database successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save lead: ' . $wpdb->last_error]);
        }
        exit;
    } else {
        // --- 2. LOCAL XAMPP ENVIRONMENT (PDO FALLBACK) ---
        $host = 'localhost';
        $db = 'local_cccinfotech';
        $user = 'root';
        $pass = ''; // Default XAMPP MySQL password is empty
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Check and create local database
            $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Connect to local database
            $pdo = new PDO($dsn, $user, $pass, $options);

            // Create local table if it doesn't exist
            $createTableSQL = "CREATE TABLE IF NOT EXISTS `shubham_das_lead` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `fullname` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `message` TEXT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $pdo->exec($createTableSQL);

            // Strip HTML tags for clean database storage to prevent XSS
            $fullname = strip_tags($fullname);
            $email = strip_tags($email);
            $message = strip_tags($message);

            // Validation checks
            $errors = [];
            if (empty($fullname)) {
                $errors[] = 'Full name is required.';
            } elseif (strlen($fullname) < 2 || strlen($fullname) > 100) {
                $errors[] = 'Full name must be between 2 and 100 characters.';
            } elseif (!preg_match("/^[a-zA-Z\s\-']+$/", $fullname)) {
                $errors[] = 'Full name must contain only letters, spaces, hyphens, or apostrophes.';
            }

            if (empty($email)) {
                $errors[] = 'Email address is required.';
            } elseif (strlen($email) > 255) {
                $errors[] = 'Email address must not exceed 255 characters.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please provide a valid email address.';
            }

            if (empty($message)) {
                $errors[] = 'Message is required.';
            } elseif (strlen($message) < 10) {
                $errors[] = 'Message must be at least 10 characters.';
            } elseif (strlen($message) > 5000) {
                $errors[] = 'Message must not exceed 5000 characters.';
            }

            if (!empty($errors)) {
                echo json_encode(['status' => 'error', 'message' => implode(' ', $errors)]);
                exit;
            }

            // Insert lead record
            $stmt = $pdo->prepare("INSERT INTO `shubham_das_lead` (`fullname`, `email`, `message`) VALUES (?, ?, ?)");
            $stmt->execute([$fullname, $email, $message]);

            echo json_encode(['status' => 'success', 'message' => 'Your message has been stored in local database successfully!']);
            exit;
        } catch (\PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shubham Das - Salesforce Consultant & Project Lead</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #F3F4F4;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --accent-blue: #00A0E3;
            --accent-blue-hover: #006ecf;
            --accent-blue-light: #e0f2fe;
            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 8px -1px rgba(0, 0, 0, 0.03);
            --transition-speed: 0.25s;
            --btn-bg-secondary: #f1f5f9;
            --btn-text-secondary: #0f172a;
            --badge-bg: #ffffff;
            --services-sidebar-bg: #f1f5f9;
            --color-error: #ef4444;
            --color-error-light: rgba(239, 68, 68, 0.15);
            --color-success: #10b981;
            --color-success-light: rgba(16, 185, 129, 0.15);
        }

        [data-theme="dark"] {
            --bg-primary: #0b0f19;
            --bg-card: #131926;
            --border-color: #1e293b;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            --accent-blue: #38bdf8;
            --accent-blue-hover: #0ea5e9;
            --accent-blue-light: #0369a1;
            --card-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            --btn-bg-secondary: #1f2937;
            --btn-text-secondary: #f3f4f6;
            --badge-bg: #1e293b;
            --services-sidebar-bg: #0b0f19;
            --color-error: #f87171;
            --color-error-light: rgba(248, 113, 113, 0.15);
            --color-success: #34d399;
            --color-success-light: rgba(52, 211, 153, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
            line-height: 1.5;
        }

        /* Layout */
        .app-layout {
            display: flex;
            max-width: 1240px;
            margin: 0 auto;
            gap: 50px;
            padding: 40px 20px;
        }

        /* Sidebar Column */
        .sidebar-column {
            width: 420px;
            flex-shrink: 0;
            position: sticky;
            top: 40px;
            height: fit-content;
        }

        .profile-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-primary);
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background var(--transition-speed), color var(--transition-speed);
        }

        .icon-btn:hover {
            background: var(--btn-bg-secondary);
        }

        .profile-img-container {
            width: 100%;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 20px;
            aspect-ratio: 1 / 1;
        }

        .profile-img {
            /* width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.1);
            transform-origin: 50% 25%; */
        }

        .profile-name {
            font-size: 24px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .profile-title {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.8px;
            color: var(--accent-blue);
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .profile-desc {
            font-size: 12.5px;
            line-height: 1.5;
            color: var(--text-secondary);
            /* margin-bottom: 20px; */
        }

        .profile-socials {
            display: flex;
            gap: 14px;
            margin-bottom: 24px;
            justify-content: center;
            align-items: center;
        }

        .social-link {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: transform var(--transition-speed);
        }

        .social-link img {
            width: 22px;
            height: 22px;
            transition: filter var(--transition-speed);
        }

        /* Light theme invert to match the dark color style of the screenshot icons */
        .social-link img {
            filter: var(--social-icon-filter, none);
        }

        [data-theme="dark"] .social-link img {
            filter: invert(1) brightness(0.9);
        }

        .social-link:hover {
            transform: translateY(-3px);
        }

        .consultation-btn {
            display: block;
            width: 100%;
            background-color: var(--accent-blue);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 500;
            text-align: center;
            padding: 12px;
            border-radius: 30px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 130, 242, 0.2);
            transition: background-color var(--transition-speed), transform var(--transition-speed);
        }

        .consultation-btn:hover {
            background-color: var(--accent-blue-hover);
            transform: translateY(-1px);
        }

        /* Tech Badges below sidebar card */
        .tech-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 20px 0px;
            justify-content: center;
        }

        .tech-badge {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 5px 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-speed);
        }

        .tech-badge img {
            height: 12px;
            width: auto;
        }

        .tech-badge:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
            transform: translateY(-2px);
        }

        /* Content Column */
        .content-column {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 60px;
            max-width: 900px;
        }

        /* Top Nav Pill */
        .nav-wrapper {
            position: sticky;
            top: 20px;
            z-index: 100;
            margin-bottom: 10px;
            width: 100%;
        }

        .nav-pill-container {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-shadow: var(--card-shadow);
            transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        .nav-link {
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-speed);
            text-align: center;
            flex: 1;
        }

        .nav-link:hover {
            color: var(--text-primary);
        }

        .nav-link.active {
            color: var(--accent-blue);
            background-color: var(--accent-blue-light);
        }

        [data-theme="dark"] .nav-link.active {
            color: #ffffff;
            background-color: var(--accent-blue-light);
        }

        /* Sections Styling */
        .content-section {
            padding-top: 10px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 0.8px;
            color: var(--text-primary);
            text-transform: uppercase;
        }

        .section-divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 14px 0 24px 0;
            border: none;
        }

        /* Hero / About section */
        .hero-subtitle {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .text-blue {
            color: var(--accent-blue);
        }

        .hero-title {
            font-size: 40px;
            font-weight: 500;
            line-height: 1.25;
            color: var(--text-primary);
            margin-bottom: 24px;
            letter-spacing: -0.5px;
        }

        .consultant-pill {
            background-color: var(--accent-blue);
            color: #ffffff;
            padding: 2px 18px;
            border-radius: 40px;
            display: inline-block;
            font-size: 38px;
            line-height: 1.1;
            font-weight: 500;
            margin-left: 2px;
            margin-right: 2px;
        }

        .stats-row {
            display: flex;
            gap: 40px;
            margin: 36px 0;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-number {
            font-size: 38px;
            font-weight: 400;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.3;
            font-weight: 500;
        }

        .about-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.8px;
            color: var(--accent-blue);
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .about-desc {
            font-size: 14.5px;
            line-height: 1.7;
            color: var(--text-secondary);
            margin-bottom: 20px;
            font-weight: 400;
        }

        /* Certifications section */
        .certs-container {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .certs-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .certs-count-box {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .certs-count-number {
            font-size: 20px;
            color: var(--accent-blue);
        }

        .certs-controls {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        .certs-search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .certs-search-wrapper .search-icon {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .certs-search-wrapper input {
            padding: 10px 16px 10px 40px;
            border: 1px solid var(--border-color);
            background-color: var(--bg-primary);
            color: var(--text-primary);
            border-radius: 12px;
            font-family: inherit;
            font-size: 13.5px;
            width: 240px;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }

        .certs-search-wrapper input:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(0, 160, 227, 0.15);
            background-color: var(--bg-card);
        }

        .certs-sort-wrapper select {
            padding: 10px 36px 10px 16px;
            border: 1px solid var(--border-color);
            background-color: var(--bg-primary);
            color: var(--text-primary);
            border-radius: 12px;
            font-family: inherit;
            font-size: 13.5px;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }

        [data-theme="dark"] .certs-sort-wrapper select {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        }

        .certs-sort-wrapper select:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(0, 160, 227, 0.15);
            background-color: var(--bg-card);
        }

        .certs-groups {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Product Group Panel */
        .cert-group-panel {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            background-color: var(--bg-card);
            transition: border-color 0.3s;
        }

        .cert-group-header {
            width: 100%;
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            gap: 14px;
            transition: background-color 0.2s;
            user-select: none;
        }

        .cert-group-header:hover {
            background-color: rgba(0, 160, 227, 0.04);
        }

        [data-theme="dark"] .cert-group-header:hover {
            background-color: rgba(56, 189, 248, 0.04);
        }

        .cert-group-header .group-chevron {
            color: var(--text-muted);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
        }

        .cert-group-panel.collapsed .group-chevron {
            transform: rotate(-90deg);
        }

        .cert-group-header .group-icon-wrapper {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--bg-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--accent-blue);
            transition: background-color 0.3s;
        }

        .cert-group-header .group-icon-wrapper svg {
            width: 20px;
            height: 20px;
        }

        .cert-group-header .group-icon-wrapper img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .cert-group-header .group-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .cert-group-header .group-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            font-family: inherit;
        }

        .cert-group-header .group-count {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* Expandable content area */
        .cert-group-content {
            max-height: 1500px;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s;
            border-top: 1px solid var(--border-color);
            padding: 8px 0;
        }

        .cert-group-panel.collapsed .cert-group-content {
            max-height: 0;
            padding-top: 0;
            padding-bottom: 0;
            border-top-color: transparent;
        }

        /* Certificate Item row */
        .cert-row {
            display: flex;
            padding: 24px 30px;
            gap: 24px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s, transform 0.2s;
            position: relative;
        }

        .cert-row:last-child {
            border-bottom: none;
        }

        .cert-row:hover {
            background-color: rgba(0, 0, 0, 0.01);
            transform: translateX(4px);
        }

        [data-theme="dark"] .cert-row:hover {
            background-color: rgba(255, 255, 255, 0.01);
        }

        .cert-badge-wrapper {
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cert-badge-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.08));
            transition: transform var(--transition-speed);
        }

        .cert-row:hover .cert-badge-wrapper img {
            transform: scale(1.06) rotate(1deg);
        }

        .cert-details {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }

        .cert-product-tag {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .cert-title-link {
            font-size: 15px;
            font-weight: 600;
            color: var(--accent-blue);
            text-decoration: none;
            line-height: 1.3;
            transition: color var(--transition-speed);
        }

        .cert-title-link:hover {
            color: var(--accent-blue-hover);
        }

        .cert-achieved {
            font-size: 12.5px;
            color: var(--text-secondary);
        }

        .cert-description {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 6px;
            line-height: 1.5;
        }

        .cert-date {
            font-size: 12px;
            color: var(--text-muted);
            align-self: flex-end;
            margin-top: 10px;
            font-weight: 500;
        }

        /* Responsive styling */
        @media (max-width: 640px) {
            .certs-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .certs-controls {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .certs-search-wrapper input {
                width: 100%;
            }

            .certs-sort-wrapper select {
                width: 100%;
            }

            .cert-row {
                flex-direction: column;
                padding: 20px;
                gap: 16px;
            }

            .cert-badge-wrapper {
                width: 70px;
                height: 70px;
                align-self: flex-start;
            }

            .cert-date {
                align-self: flex-start;
                margin-top: 8px;
            }
        }

        /* Empty/No Results state */
        .certs-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            text-align: center;
            color: var(--text-muted);
            gap: 12px;
        }

        .certs-empty-icon {
            color: var(--text-muted);
            opacity: 0.5;
        }

        .certs-empty-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .certs-empty-desc {
            font-size: 13px;
            max-width: 320px;
        }

        /* Copado Specific styles */
        .cert-product-tag[data-tag="Copado"] {
            color: #00B2A9 !important;
        }

        .cert-title-link[data-tag="Copado"] {
            color: #00B2A9;
        }

        .cert-title-link[data-tag="Copado"]:hover {
            color: #00807a;
        }

        /* Modal Overlay Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            /* Slate backdrop */
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-container {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            width: 100%;
            max-width: 650px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
            transform: scale(0.9);
            transition: transform 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-overlay.active .modal-container {
            transform: scale(1);
        }

        .modal-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background var(--transition-speed), color var(--transition-speed);
        }

        .modal-close-btn:hover {
            background: var(--btn-bg-secondary);
            color: var(--text-primary);
        }

        /* Contact CTA Block (replaces form inline) */
        .contact-cta-block {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-top: 40px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .contact-cta-block .form-title {
            margin-bottom: 0;
            font-size: 24px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .contact-cta-desc {
            font-size: 14px;
            color: var(--text-secondary);
            max-width: 480px;
            line-height: 1.6;
        }

        /* Services section */
        .services-container {
            display: flex;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            margin-top: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
            transition: background-color 0.3s, border-color 0.3s;
        }

        .services-sidebar {
            width: 240px;
            flex-shrink: 0;
            border-right: 1px solid var(--border-color);
            padding: 24px 16px;
            background: var(--services-sidebar-bg);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .service-tab-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            background: none;
            border: none;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            text-align: left;
            border-radius: 12px;
            transition: all var(--transition-speed);
        }

        .service-tab-btn:hover:not(.active) {
            background-color: rgba(0, 0, 0, 0.03);
            color: var(--text-primary);
        }

        [data-theme="dark"] .service-tab-btn:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .service-tab-btn.active {
            background-color: var(--bg-card);
            color: var(--accent-blue);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .chevron-icon {
            opacity: 0;
            transition: opacity var(--transition-speed), transform var(--transition-speed);
        }

        .service-tab-btn.active .chevron-icon {
            opacity: 1;
            stroke: var(--accent-blue);
            transform: translateX(3px);
        }

        .services-content {
            flex-grow: 1;
            min-width: 0;
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            background: var(--bg-card);
            transition: background-color 0.3s;
        }

        .services-content-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .services-tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-content: flex-start;
        }

        .service-tag {
            background: var(--btn-bg-secondary);
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-speed);
        }

        .service-tag:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
            background: var(--accent-blue-light);
            transform: translateY(-2px);
        }

        /* Top Clients section */
        /* Top Clients section */
        .clients-slider-container {
            overflow: hidden;
            width: 100%;
            position: relative;
            padding: 16px 0;
            margin-top: 10px;
            /* Premium fade gradient at the edges */
            mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent);
        }

        .clients-slider-track {
            display: flex;
            width: max-content;
            animation: marquee 25s linear infinite;
            will-change: transform;
        }

        .clients-slider-track:hover {
            animation-play-state: paused;
        }

        .clients-slider-list {
            display: flex;
            align-items: center;
            gap: 30px;
            padding-right: 30px;
        }

        .client-logo-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            background-color: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: transform var(--transition-speed), box-shadow var(--transition-speed);
        }

        .client-logo-wrapper:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
        }

        .client-logo-wrapper.logo-bg-gray {
            background-color: #334155 !important;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .client-logo {
            height: 32px;
            max-width: 110px;
            object-fit: contain;
            display: block;
        }

        @keyframes marquee {
            0% {
                transform: translate3d(0, 0, 0);
            }

            100% {
                transform: translate3d(-50%, 0, 0);
            }
        }

        /* Contact section */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 10px;
        }

        .contact-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            transition: all var(--transition-speed);
        }

        .contact-card:hover {
            transform: translateY(-3px);
            border-color: var(--accent-blue);
            box-shadow: var(--card-shadow);
        }

        .contact-card-full {
            grid-column: span 2;
        }

        .contact-card-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .contact-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        [data-theme="dark"] .contact-icon {
            filter: invert(1) brightness(0.9);
        }

        .contact-card-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
        }

        .contact-card-detail {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-align: right;
        }

        /* Contact Form */
        .contact-form-section {
            margin-top: 40px;
            padding-bottom: 80px;
        }

        .form-title {
            font-size: 26px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 24px;
        }

        .project-contact-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-primary);
            font-family: inherit;
            transition: all var(--transition-speed);
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px var(--accent-blue-light);
        }

        .submit-btn {
            align-self: flex-start;
            background: var(--btn-bg-secondary);
            color: var(--btn-text-secondary);
            border: none;
            border-radius: 30px;
            padding: 12px 24px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all var(--transition-speed);
            font-family: inherit;
        }

        .submit-btn:hover {
            background: var(--accent-blue);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .submit-btn svg {
            transition: transform var(--transition-speed);
        }

        .submit-btn:hover svg {
            transform: translateX(3px);
        }

        /* Form Validation Custom Styles */
        .form-group {
            position: relative;
        }

        .form-group input,
        .form-group textarea {
            border: 1px solid var(--border-color);
            transition: border-color var(--transition-speed), box-shadow var(--transition-speed);
        }

        /* Error States */
        .form-group.has-error input,
        .form-group.has-error textarea {
            border-color: var(--color-error) !important;
        }

        .form-group.has-error input:focus,
        .form-group.has-error textarea:focus {
            box-shadow: 0 0 0 3px var(--color-error-light) !important;
            border-color: var(--color-error) !important;
        }

        /* Success States */
        .form-group.has-success input,
        .form-group.has-success textarea {
            border-color: var(--color-success) !important;
        }

        .form-group.has-success input:focus,
        .form-group.has-success textarea:focus {
            box-shadow: 0 0 0 3px var(--color-success-light) !important;
            border-color: var(--color-success) !important;
        }

        /* Error message text */
        .error-message {
            display: none;
            color: var(--color-error);
            font-size: 11px;
            font-weight: 500;
            margin-top: 5px;
            margin-left: 4px;
            align-items: center;
            gap: 6px;
            animation: formErrorFadeIn 0.25s ease-out forwards;
        }

        .form-group.has-error .error-message {
            display: flex;
        }

        @keyframes formErrorFadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Success Screen Style */
        .form-success-container {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            animation: successContainerFadeIn 0.5s ease-out forwards;
        }

        @keyframes successContainerFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .success-icon-wrapper {
            width: 72px;
            height: 72px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Checkmark Animation */
        .checkmark {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: block;
            stroke-width: 3;
            stroke: var(--color-success);
            stroke-miterlimit: 10;
            box-shadow: inset 0px 0px 0px var(--color-success);
            animation: fillCheckmark .4s ease-in-out .4s forwards, scaleCheckmark .3s ease-in-out .9s forwards;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 3;
            stroke-miterlimit: 10;
            stroke: var(--color-success);
            fill: none;
            animation: strokeCheckmark 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            stroke: #ffffff;
            animation: strokeCheckmark 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
        }

        @keyframes strokeCheckmark {
            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes scaleCheckmark {

            0%,
            100% {
                transform: none;
            }

            50% {
                transform: scale3d(1.15, 1.15, 1);
            }
        }

        @keyframes fillCheckmark {
            100% {
                box-shadow: inset 0px 0px 0px 40px var(--color-success);
            }
        }

        .success-title {
            font-size: 22px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .success-subtitle {
            font-size: 13.5px;
            color: var(--text-secondary);
            max-width: 380px;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .success-btn {
            background: var(--btn-bg-secondary);
            color: var(--btn-text-secondary);
            border: 1px solid var(--border-color);
            border-radius: 30px;
            padding: 10px 24px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-speed);
            font-family: inherit;
        }

        .success-btn:hover {
            background: var(--accent-blue);
            color: #ffffff;
            border-color: var(--accent-blue);
            transform: translateY(-2px);
        }

        /* Responsive Breakpoints */
        @media (min-width: 993px) and (max-width: 1200px) {
            .app-layout {
                gap: 30px;
                padding: 30px 20px;
            }

            .sidebar-column {
                width: 340px;
            }

            .profile-card {
                padding: 20px;
            }

            .profile-name {
                font-size: 22px;
            }

            .profile-desc {
                font-size: 12px;
            }

            .nav-link {
                padding: 8px 12px;
                font-size: 12px;
            }

            .stats-row {
                gap: 20px;
                margin: 24px 0;
            }
        }

        @media (max-width: 992px) {
            .app-layout {
                flex-direction: column;
                gap: 40px;
                padding: 30px 20px;
            }

            .sidebar-column {
                width: 100%;
                position: relative;
                top: 0;
            }

            .profile-card {
                max-width: 600px;
                margin: 0 auto;
            }

            .tech-badges {
                max-width: 600px;
                margin: 24px auto 0 auto;
            }

            .nav-wrapper {
                position: sticky;
                top: 10px;
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .nav-pill-container {
                width: 100%;
                max-width: 100%;
                display: flex;
                justify-content: space-between;
                overflow-x: auto;
                white-space: nowrap;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                /* Hide scrollbar for Firefox */
            }

            .nav-pill-container::-webkit-scrollbar {
                display: none;
                /* Hide scrollbar for Chrome, Safari, Opera */
            }

            .nav-link {
                flex: 0 0 auto;
                padding: 8px 16px;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 32px;
            }

            .consultant-pill {
                font-size: 30px;
                padding: 1px 14px;
            }

            .services-container {
                flex-direction: column;
            }

            .services-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding: 12px;
                flex-direction: row;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                gap: 8px;
                scrollbar-width: none;
                /* Hide scrollbar for Firefox */
            }

            .services-sidebar::-webkit-scrollbar {
                display: none;
                /* Hide scrollbar for Chrome, Safari, Opera */
            }

            .service-tab-btn {
                width: auto;
                flex: 0 0 auto;
                padding: 8px 16px;
                font-size: 13px;
                justify-content: center;
            }

            .chevron-icon {
                display: none;
                /* Hide chevron on horizontal tabs */
            }

            .services-content {
                padding: 24px 16px;
                gap: 16px;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .contact-card-full {
                grid-column: span 1;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .clients-slider-list {
                gap: 20px;
                padding-right: 20px;
            }

            .client-logo-wrapper {
                padding: 6px 12px;
                border-radius: 8px;
            }

            .client-logo {
                height: 22px;
                max-width: 80px;
            }
        }

        @media (max-width: 480px) {
            .app-layout {
                padding: 15px 12px;
                gap: 30px;
            }

            .nav-pill-container {
                padding: 4px;
            }

            .nav-link {
                padding: 6px 12px;
                font-size: 12px;
            }

            .hero-title {
                font-size: 26px;
            }

            .consultant-pill {
                font-size: 24px;
                padding: 0 10px;
            }

            .stats-row {
                gap: 20px;
                justify-content: space-between;
            }

            .stat-item {
                flex: 1 1 30%;
                min-width: 90px;
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .stat-number {
                font-size: 28px;
            }

            .stat-label {
                font-size: 11px;
            }

            .clients-slider-list {
                gap: 15px;
                padding-right: 15px;
            }

            .client-logo-wrapper {
                padding: 4px 8px;
                border-radius: 6px;
            }

            .client-logo {
                height: 18px;
                max-width: 60px;
            }

            .contact-card {
                padding: 16px;
            }

            .contact-card-label {
                font-size: 12px;
            }

            .contact-card-detail {
                font-size: 12px;
                word-break: break-word;
                line-height: 1.4;
            }
        }
    </style>
</head>

<body>

    <div class="app-layout">
        <!-- Sidebar Column -->
        <aside class="sidebar-column">
            <div class="profile-card">
                <div class="sidebar-header">
                    <button class="icon-btn" aria-label="Menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" y1="12" x2="20" y2="12"></line>
                            <line x1="4" y1="6" x2="20" y2="6"></line>
                            <line x1="4" y1="18" x2="20" y2="18"></line>
                        </svg>
                    </button>
                    <button class="icon-btn theme-toggle" id="theme-toggle-btn" aria-label="Toggle Theme">
                        <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </button>
                </div>
                <div class="profile-img-container">
                    <img src="profile_cutout.png" alt="Shubham Das" class="profile-img" id="avatar-img" />
                </div>
                <h1 class="profile-name">Shubham Das</h1>
                <div class="profile-title">Salesforce Consultant & Project Lead</div>
                <p class="profile-desc">
                    10x Certified | Sales, Service, Marketing & Data Cloud + Agentforce | Apex • LWC | HubSpot | Helping businesses turn CRM into revenue
                </p>
                <!-- Technology Skill Badges -->
                <div class="tech-badges">
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/sales-cloud.svg" alt="Sales Cloud">
                        <span>Sales Cloud</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/service-cloud.svg" alt="Service Cloud">
                        <span>Service Cloud</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/marketing-cloud.svg" alt="Marketing Cloud">
                        <span>Marketing Cloud</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/data-cloud.svg" alt="Data Cloud">
                        <span>Data Cloud</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/experience-cloud.svg" alt="Experience Cloud">
                        <span>Experience Cloud</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/agentforce-icon.svg" alt="Agentforce">
                        <span>Agentforce</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/salesforce-icon.svg" alt="Apex">
                        <span>Apex</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/lwc-icon.svg" alt="LWC">
                        <span>LWC</span>
                    </div>
                    <div class="tech-badge">
                        <img src="images/salesforce-icons/hubspot-icon.svg" alt="HubSpot">
                        <span>HubSpot</span>
                    </div>
                </div>
                <div class="profile-socials">
                    <a href="https://wa.me/+919583162067" target="_blank" class="social-link" title="WhatsApp">
                        <img src="images/social-media/whatsapp.svg" alt="WhatsApp">
                    </a>
                    <a href="https://www.linkedin.com/in/shubhamdas28/" target="_blank" class="social-link" title="LinkedIn">
                        <img src="images/social-media/linkedin.svg" alt="LinkedIn">
                    </a>
                    <a href="https://cccinfotech.slack.com/team/U06BBKN6S73" target="_blank" class="social-link" title="Slack">
                        <img src="images/social-media/slack.svg" alt="Slack">
                    </a>
                    <a href="mailto:shubham@cccinfotech.com" class="social-link" title="Email">
                        <img src="images/social-media/mail.svg" alt="Email">
                    </a>
                    <a href="http://cccinfotech.com/" target="_blank" class="social-link" title="Website">
                        <img src="images/social-media/web.svg" alt="Website">
                    </a>
                </div>
                <a href="#" class="consultation-btn" id="open-consultation-btn">Get Free Consultation</a>
            </div>


        </aside>

        <!-- Content Column -->
        <main class="content-column">
            <!-- Sticky Navigation -->
            <div class="nav-wrapper">
                <nav class="nav-pill-container" id="main-nav">
                    <a href="#about" class="nav-link active">About</a>
                    <a href="#certifications" class="nav-link">Certifications</a>
                    <!-- <a href="#copado-certifications" class="nav-link">Copado Certs</a> -->
                    <a href="#services" class="nav-link">Services</a>
                    <a href="#clients" class="nav-link">Clients</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </nav>
            </div>

            <!-- About / Hero Section -->
            <section id="about" class="content-section">
                <p class="hero-subtitle">Hello, I’m <span class="text-blue">Shubham</span></p>
                <h1 class="hero-title">
                    Certified Salesforce &<br>
                    Artificial Intelligence <span class="text-blue">Consultant</span><br>

                </h1>

                <!-- Statistics Row -->
                <div class="stats-row">
                    <div class="stat-item">
                        <span class="stat-number">10+</span>
                        <span class="stat-label">Years of<br>Experience</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Completed<br>Projects</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">10x</span>
                        <span class="stat-label">Certified</span>
                    </div>
                </div>

                <div class="about-label">ABOUT ME</div>
                <p class="about-desc">
                    Salesforce Consultant and Project Lead with 10+ years designing, building, and scaling CRM solutions for growing businesses at CloudCentric Infotech — a Summit Salesforce Partner.
                </p>
                <p class="about-desc">
                    I lead end-to-end Salesforce delivery at CloudCentric Infotech from requirements and solution design through build, QA, and go-live. Managing teams of up to 10 developers and admins across multiple industries. I'm 10x Salesforce certified with hands-on depth in Apex, Lightning Web Components, configuration and automation, and I also consult on HubSpot CRM.
                </p>
            </section>

            <!-- Certifications Section -->
            <section id="certifications" class="content-section">
                <h2 class="section-title">CERTIFICATIONS</h2>
                <hr class="section-divider">

                <!-- Certifications Interactive Container -->
                <div class="certs-container">
                    <!-- Header with count, search and sort -->
                    <div class="certs-header">
                        <div class="certs-count-box">
                            <span class="certs-count-number" id="certs-count">12</span> Certifications
                        </div>
                        <div class="certs-controls">
                            <div class="certs-search-wrapper">
                                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" id="cert-search" placeholder="Quick search" aria-label="Search Certifications">
                            </div>
                            <div class="certs-sort-wrapper">
                                <select id="cert-sort" aria-label="Sort Certifications">
                                    <option value="product-asc">Sort by Product (A-Z)</option>
                                    <option value="product-desc">Sort by Product (Z-A)</option>
                                    <option value="date-desc">Sort by Date (Newest first)</option>
                                    <option value="date-asc">Sort by Date (Oldest first)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Certifications Groups List -->
                    <div class="certs-groups" id="certs-groups-list">
                        <!-- Dynamic content will be rendered here via JavaScript -->
                    </div>
                </div>
            </section>

            <!-- Services Section -->
            <section id="services" class="content-section">
                <h2 class="section-title">SERVICES</h2>
                <hr class="section-divider">
                <div class="services-container">
                    <div class="services-sidebar">
                        <button class="service-tab-btn active" data-tab="salesforce-services">
                            <span>Salesforce Services</span>
                            <svg class="chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                        <button class="service-tab-btn" data-tab="salesforce-clouds">
                            <span>Salesforce Clouds</span>
                            <svg class="chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                        <button class="service-tab-btn" data-tab="salesforce-products">
                            <span>Salesforce Products</span>
                            <svg class="chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                    <div class="services-content" id="services-tags-container">
                        <!-- Render tags dynamically here via JS -->
                    </div>
                </div>
            </section>

            <!-- Top Clients Section -->
            <section id="clients" class="content-section">
                <h2 class="section-title">TOP CLIENTS</h2>
                <hr class="section-divider">
                <div class="clients-slider-container">
                    <div class="clients-slider-track" style="animation-duration: 10s;">
                        <div class="clients-slider-list">
                            <div class="client-logo-wrapper">
                                <img src="images/clients/codingdepartment.svg" alt="Coding Department" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/novo.svg" alt="Novo" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/sirona.svg" alt="Sirona" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/ferrum.svg" alt="Ferrum" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/aidoc.svg" alt="Aidoc" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/celularity.png" alt="Celularity" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/cropped-chiropractic.png" alt="Chiropractic" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/esg-book.png" alt="ESG Book" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/modifi.png" alt="Modifi" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/muvi.png" alt="Muvi" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper logo-bg-gray">
                                <img src="images/clients/paro.svg" alt="Paro" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/safecontractor.svg" alt="SafeContractor" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/startek.png" alt="Startek" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper logo-bg-gray">
                                <img src="images/clients/surveytogo.png" alt="SurveyToGo" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/urbn-dental.png" alt="URBN Dental" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/valley-force.png" alt="Valley Force" class="client-logo">
                            </div>
                        </div>
                        <div class="clients-slider-list">
                            <div class="client-logo-wrapper">
                                <img src="images/clients/codingdepartment.svg" alt="Coding Department" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/novo.svg" alt="Novo" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/sirona.svg" alt="Sirona" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/ferrum.svg" alt="Ferrum" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/aidoc.svg" alt="Aidoc" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/celularity.png" alt="Celularity" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/cropped-chiropractic.png" alt="Chiropractic" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/esg-book.png" alt="ESG Book" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/modifi.png" alt="Modifi" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/muvi.png" alt="Muvi" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper logo-bg-gray">
                                <img src="images/clients/paro.svg" alt="Paro" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/safecontractor.svg" alt="SafeContractor" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/startek.png" alt="Startek" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper logo-bg-gray">
                                <img src="images/clients/surveytogo.png" alt="SurveyToGo" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/urbn-dental.png" alt="URBN Dental" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/valley-force.png" alt="Valley Force" class="client-logo">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="content-section">
                <h2 class="section-title">CONTACT</h2>
                <hr class="section-divider">
                <div class="contact-grid">
                    <a href="tel:+919583162067" class="contact-card">
                        <div class="contact-card-left">
                            <img src="images/contact/phone-icon.svg" alt="Phone" class="contact-icon">
                            <span class="contact-card-label">Phone</span>
                        </div>
                        <span class="contact-card-detail">+91 9583162067</span>
                    </a>
                    <a href="mailto:shubham@cccinfotech.com" class="contact-card">
                        <div class="contact-card-left">
                            <img src="images/contact/email-icon.svg" alt="Email" class="contact-icon">
                            <span class="contact-card-label">Email</span>
                        </div>
                        <span class="contact-card-detail">shubham@cccinfotech.COM</span>
                    </a>
                    <div class="contact-card contact-card-full">
                        <div class="contact-card-left">
                            <img src="images/contact/address-icon.svg" alt="Address" class="contact-icon">
                            <span class="contact-card-label">India Office Address</span>
                        </div>
                        <span class="contact-card-detail">h-146 & 147, h Block, Sector 63, Noida, Uttar Pradesh 201309</span>
                    </div>
                    <div class="contact-card contact-card-full">
                        <div class="contact-card-left">
                            <img src="images/contact/address-icon.svg" alt="Address" class="contact-icon">
                            <span class="contact-card-label">USA Office Address</span>
                        </div>
                        <span class="contact-card-detail">8357 Emerald Winds Cir, Boynton Beach, FL 33473 United States</span>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Contact Form Popup Modal -->
    <div id="contact-modal" class="modal-overlay">
        <div class="modal-container">
            <button class="modal-close-btn" id="modal-close-btn" aria-label="Close modal">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div class="contact-form-section" style="margin-top: 0; padding-bottom: 0;">
                <h3 class="form-title" id="form-section-title">Let's make your project brilliant!</h3>

                <form class="project-contact-form" id="contact-form" action="#" method="POST" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
                            <div class="error-message" id="fullname-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <span>Please enter your full name (minimum 2 characters).</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Email Address" required>
                            <div class="error-message" id="email-error">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <span>Please enter a valid email address.</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                        <div class="error-message" id="message-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            <span>Please enter a message (minimum 10 characters).</span>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">
                        <span>SEND MESSAGE</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </form>

                <!-- Success Message Block -->
                <div class="form-success-container" id="form-success">
                    <div class="success-icon-wrapper">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                        </svg>
                    </div>
                    <h4 class="success-title">Message Sent!</h4>
                    <p class="success-subtitle">Thank you for reaching out. Your message has been sent successfully, and Shubham will get back to you shortly.</p>
                    <button type="button" class="success-btn" id="success-reset-btn">Send Another Message</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Use proper photo if available
        const img = document.getElementById('avatar-img');
        img.onerror = function() {
            this.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="%23475569"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></svg>';
        }

        // Theme Toggle Script
        const themeToggleBtn = document.getElementById('theme-toggle-btn');
        const moonIcon = document.querySelector('.moon-icon');
        const sunIcon = document.querySelector('.sun-icon');

        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateThemeIcons(savedTheme);

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcons(newTheme);
        });

        function updateThemeIcons(theme) {
            if (theme === 'dark') {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'block';
            } else {
                moonIcon.style.display = 'block';
                sunIcon.style.display = 'none';
            }
        }

        // Certifications Data & Interactive Logic
        const certificationsData = [{
                title: "Salesforce Certified Agentforce Specialist",
                category: "Agentforce",
                tag: "Agentforce",
                image: "images/certificates/agentforce-specialist.svg",
                description: "Certified Agentforce Specialists are responsible for managing and optimizing Agentforce and have a deep understanding of both Salesforce platform configuration and Agentforce capabilities.",
                date: "2024-12",
                issuedText: "Issued Dec 2024"
            },
            {
                title: "Salesforce Certified AI Associate",
                category: "Agentforce",
                tag: "Agentforce",
                image: "images/certificates/ai-associate.svg",
                description: "Certified AI Associates should be able to provide informed strategies and guide stakeholder decisions based on Salesforce's Trusted AI Principles.",
                date: "2024-11",
                issuedText: "Issued Nov 2024"
            },
            {
                title: "Salesforce Certified Agentforce Sales Consultant",
                category: "Sales",
                tag: "Sales",
                image: "images/certificates/agentforce-sales-consultant.svg",
                description: "Certified Agentforce Sales Consultants are trained to design and implement Agentforce Sales solutions that are sustainable, scalable, and contribute to long-term customer success.",
                date: "2020-11",
                issuedText: "Issued Nov 2020"
            },
            {
                title: "Salesforce Certified Agentforce Service Consultant",
                category: "Service",
                tag: "Service",
                image: "images/certificates/agentforce-service-consultant.svg",
                description: "Certified Agentforce Service Consultants are experts at designing and implementing Agentforce Service solutions that are sustainable and scalable, meet customer business requirements, and contribute to long-term customer success.",
                date: "2020-08",
                issuedText: "Issued Aug 2020"
            },
            {
                title: "Salesforce Certified Data 360 Consultant",
                category: "Data 360",
                tag: "Data 360",
                image: "images/certificates/data-360-consultant.svg",
                description: "Certified Data 360 Consultants have experience implementing and consulting on enterprise data platforms in a customer-facing role.",
                date: "2025-01",
                issuedText: "Issued Jan 2025"
            },
            {
                title: "Salesforce Certified Platform Administrator",
                category: "Platform",
                tag: "Platform",
                image: "images/certificates/platform-administrator.svg",
                description: "Certified Platform Administrators are Salesforce professionals who build and manage trusted solutions on the Salesforce Platform. They administer and secure the lifecycle of users, data, apps and agents to ensure org health and maximize value.",
                date: "2018-02",
                issuedText: "Issued Feb 2018"
            },
            {
                title: "Salesforce Certified Platform App Builder",
                category: "Platform",
                tag: "Platform",
                image: "images/certificates/platform-app-builder.svg",
                description: "Certified Platform App Builders have the skills and knowledge to design, build, and implement custom applications using the declarative customization capabilities of the Salesforce Platform.",
                date: "2019-07",
                issuedText: "Issued Jul 2019"
            },
            {
                title: "Salesforce Certified Platform Developer",
                category: "Platform",
                tag: "Platform",
                image: "images/certificates/platform-developer.svg",
                description: "Certified Platform Developers understand how to develop and deploy custom business logic and custom interfaces using the programmatic capabilities of the Lightning Platform. They can also extend the Lightning Platform using Apex and Visualforce.",
                date: "2018-10",
                issuedText: "Issued Oct 2018"
            },
            {
                title: "Salesforce Certified Platform Developer II",
                category: "Platform",
                tag: "Platform",
                image: "images/certificates/platform-developer-2.svg",
                description: "Certified Platform Developer II (PDII) developers are experts in the advanced programmatic capabilities of the Salesforce Platform, as well as using data modeling to develop complex business logic and interfaces.",
                date: "2019-04",
                issuedText: "Issued Apr 2019"
            },
            {
                title: "Salesforce Certified Platform Foundations",
                category: "Platform",
                tag: "Platform",
                image: "images/certificates/platform-foundations.svg",
                description: "The Salesforce Platform Foundations exam is designed for users with a fundamental awareness of how an integrated CRM platform solves the challenge of connecting departments and customer data, and who have up to 6 months of Salesforce user experience.",
                date: "2023-05",
                issuedText: "Issued May 2023"
            },
            {
                title: "Fundamentals I Metadata Pipeline Certification",
                category: "Copado",
                tag: "Copado",
                image: "images/certificates/Copado-Fundamentals-I-Badge.png",
                description: "Demonstrates fundamental knowledge of Copado's metadata pipeline, deployment processes, version control integration, and environment management within Salesforce DevOps.",
                date: "2020-06",
                issuedText: "Issued Jun 21 2020"
            },
            {
                title: "Fundamentals II Metadata Pipeline Certification",
                category: "Copado",
                tag: "Copado",
                image: "images/certificates/Copado-Fundamentals-2-Badge.png",
                description: "Validates advanced expertise in configuring, customizing, and troubleshooting Copado's metadata pipeline, including branching strategies, quality gates, and automated testing integrations.",
                date: "2020-08",
                issuedText: "Issued Aug 8 2020"
            }
        ];

        // Track collapse state of categories to persist it during search/sort refresh
        const categoryCollapseStates = {
            "Agentforce": true, // Collapsed by default
            "Sales": true, // Collapsed by default
            "Service": true, // Collapsed by default
            "Copado": true, // Collapsed by default
            "Data 360": true, // Collapsed by default
            "Platform": true // Collapsed by default
        };

        const certsGroupsContainer = document.getElementById('certs-groups-list');
        const certsCountEl = document.getElementById('certs-count');
        const certSearchInput = document.getElementById('cert-search');
        const certSortSelect = document.getElementById('cert-sort');

        // Reference icons
        const productIcons = {
            "Agentforce": `<img src="images/salesforce-icons/agentforce-icon.svg" alt="Agentforce" />`,
            "Sales": `<img src="images/salesforce-icons/sales-cloud.svg" alt="Sales" />`,
            "Service": `<img src="images/salesforce-icons/service-cloud.svg" alt="Service" />`,
            "Copado": `<img src="images/certificates/copado.png" alt="Copado" />`,
            "Data 360": `<img src="images/salesforce-icons/data-cloud.svg" alt="Data 360" />`,
            "Platform": `<img src="images/salesforce-icons/salesforce-icon.svg" alt="Platform" />`
        };

        function renderCertifications() {
            const searchTerm = certSearchInput.value.toLowerCase().trim();
            const sortBy = certSortSelect.value;

            // Filter data
            let filteredCerts = certificationsData.filter(cert => {
                return cert.title.toLowerCase().includes(searchTerm) ||
                    cert.description.toLowerCase().includes(searchTerm) ||
                    cert.category.toLowerCase().includes(searchTerm);
            });

            // Update total count
            certsCountEl.textContent = filteredCerts.length;

            if (filteredCerts.length === 0) {
                certsGroupsContainer.innerHTML = `
                    <div class="certs-empty">
                        <svg class="certs-empty-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div class="certs-empty-title">No Certifications Found</div>
                        <div class="certs-empty-desc">Try adjusting your keywords to find what you're looking for.</div>
                    </div>
                `;
                return;
            }

            certsGroupsContainer.innerHTML = '';

            // Check if grouped or flat
            if (sortBy.startsWith('product-')) {
                // Group by product category
                const groups = {};
                filteredCerts.forEach(cert => {
                    if (!groups[cert.category]) {
                        groups[cert.category] = [];
                    }
                    groups[cert.category].push(cert);
                });

                // Sort inside each group by date descending (standard practice)
                Object.keys(groups).forEach(cat => {
                    groups[cat].sort((a, b) => b.date.localeCompare(a.date));
                });

                // Sort categories using custom order (Copado after Service)
                const categoryOrder = [
                    "Agentforce",
                    "Data 360",
                    "Platform",
                    "Sales",
                    "Service",
                    "Copado"
                ];

                const categories = Object.keys(groups).sort((a, b) => {
                    let idxA = categoryOrder.indexOf(a);
                    let idxB = categoryOrder.indexOf(b);
                    if (idxA === -1) idxA = 999;
                    if (idxB === -1) idxB = 999;
                    if (sortBy === 'product-asc') {
                        return idxA - idxB;
                    } else {
                        return idxB - idxA;
                    }
                });

                categories.forEach(cat => {
                    const certs = groups[cat];
                    const countText = certs.length === 1 ? '1 Certification' : `${certs.length} Certifications`;
                    const isCollapsed = categoryCollapseStates[cat] !== false; // default false means expanded

                    const panel = document.createElement('div');
                    panel.className = `cert-group-panel${isCollapsed ? ' collapsed' : ''}`;
                    panel.dataset.category = cat;

                    panel.innerHTML = `
                        <button class="cert-group-header" aria-expanded="${!isCollapsed}">
                            <svg class="group-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            <div class="group-icon-wrapper"${cat === 'Copado' ? ' style="color: #00B2A9; background-color: rgba(0, 178, 169, 0.1);"' : ''}>
                                ${productIcons[cat] || ''}
                            </div>
                            <div class="group-info">
                                <span class="group-title">${cat}</span>
                                <span class="group-count">${countText}</span>
                            </div>
                        </button>
                        <div class="cert-group-content">
                            ${certs.map(cert => `
                                <div class="cert-row">
                                    <div class="cert-badge-wrapper">
                                        <img src="${cert.image}" alt="${cert.title}">
                                    </div>
                                    <div class="cert-details">
                                        <span class="cert-product-tag" data-tag="${cert.tag}">${cert.tag}</span>
                                        <a href="#certifications" class="cert-title-link" data-tag="${cert.tag}">${cert.title}</a>
                                        <span class="cert-achieved">Achieved by Shubham Das</span>
                                        <p class="cert-description">${cert.description}</p>
                                        <span class="cert-date">${cert.issuedText}</span>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    `;

                    // Hook up toggle listener
                    const headerBtn = panel.querySelector('.cert-group-header');
                    headerBtn.addEventListener('click', () => {
                        const collapsed = panel.classList.toggle('collapsed');
                        headerBtn.setAttribute('aria-expanded', !collapsed);
                        categoryCollapseStates[cat] = collapsed;
                    });

                    certsGroupsContainer.appendChild(panel);
                });
            } else {
                // Flat layout sorted by Date
                filteredCerts.sort((a, b) => {
                    if (sortBy === 'date-desc') {
                        return b.date.localeCompare(a.date);
                    } else {
                        return a.date.localeCompare(b);
                    }
                });

                const panel = document.createElement('div');
                panel.className = 'cert-group-panel';
                panel.innerHTML = `
                    <div class="cert-group-content" style="border-top: none;">
                        ${filteredCerts.map(cert => `
                            <div class="cert-row">
                                <div class="cert-badge-wrapper">
                                    <img src="${cert.image}" alt="${cert.title}">
                                </div>
                                <div class="cert-details">
                                    <span class="cert-product-tag" data-tag="${cert.tag}">${cert.tag}</span>
                                    <a href="#certifications" class="cert-title-link" data-tag="${cert.tag}">${cert.title}</a>
                                    <span class="cert-achieved">Achieved by Shubham Das</span>
                                    <p class="cert-description">${cert.description}</p>
                                    <span class="cert-date">${cert.issuedText}</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
                certsGroupsContainer.appendChild(panel);
            }
        }

        // Initialize listeners
        certSearchInput.addEventListener('input', renderCertifications);
        certSortSelect.addEventListener('change', renderCertifications);

        // Initial render
        renderCertifications();

        // Copado Certifications Data & Interactive Logic was integrated into Salesforce Certifications

        // Services Tab Switcher Script
        const servicesData = {
            'salesforce-services': [
                'Salesforce Consulting',
                'Salesforce Implementation',
                'Salesforce Development',
                'Salesforce Integration',
                'Salesforce Data Migration',
                'Salesforce Training',
                'Salesforce AppExchange',
                'Hire Salesforce Consultant',
                'Salesforce Reseller',
                'Salesforce Mobile SDK Development',
                'Salesforce Support'
            ],
            'salesforce-clouds': [
                'Sales Cloud',
                'Service Cloud',
                'Marketing Cloud',
                'Commerce Cloud',
                'Experience Cloud',
                'Data Cloud',
                'Financial Services Cloud',
                'Health Cloud',
                'Education Cloud',
                'Non-Profit Cloud'
            ],
            'salesforce-products': [
                'Agentforce',
                'MuleSoft',
                'Tableau',
                'Slack',
                'CPQ & Billing',
                'Field Service Lightning',
                'Einstein AI & Analytics',
                'HubSpot Integration',
                'Salesforce Shield'
            ]
        };

        const tabButtons = document.querySelectorAll('.service-tab-btn');
        const tagsContainer = document.getElementById('services-tags-container');

        function renderTags(tabName) {
            tagsContainer.innerHTML = '';

            // Get human-readable title
            let titleText = '';
            if (tabName === 'salesforce-services') titleText = 'Salesforce Services';
            else if (tabName === 'salesforce-clouds') titleText = 'Salesforce Clouds';
            else if (tabName === 'salesforce-products') titleText = 'Salesforce Products';

            // Create title header
            const titleEl = document.createElement('h3');
            titleEl.className = 'services-content-title';
            titleEl.textContent = titleText;
            tagsContainer.appendChild(titleEl);

            // Create wrapper for tags
            const tagsList = document.createElement('div');
            tagsList.className = 'services-tags-list';

            const tags = servicesData[tabName] || [];
            tags.forEach(tag => {
                const span = document.createElement('span');
                span.className = 'service-tag';
                span.textContent = tag;
                tagsList.appendChild(span);
            });
            tagsContainer.appendChild(tagsList);
        }

        renderTags('salesforce-services');

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                renderTags(btn.getAttribute('data-tab'));
            });
        });

        // Navigation Scrollspy Script
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');
        const navContainer = document.getElementById('main-nav');

        function centerActiveNavLink() {
            const activeLink = navContainer.querySelector('.nav-link.active');
            if (activeLink) {
                const containerWidth = navContainer.offsetWidth;
                const linkLeft = activeLink.offsetLeft;
                const linkWidth = activeLink.offsetWidth;
                navContainer.scrollTo({
                    left: linkLeft - (containerWidth / 2) + (linkWidth / 2),
                    behavior: 'smooth'
                });
            }
        }

        window.addEventListener('scroll', () => {
            let current = 'about';
            const pageYOffset = window.pageYOffset;

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= (sectionTop - 150)) {
                    current = section.getAttribute('id');
                }
            });

            let changed = false;
            navLinks.forEach(link => {
                const wasActive = link.classList.contains('active');
                const isNowActive = link.getAttribute('href') === `#${current}`;
                if (isNowActive) {
                    link.classList.add('active');
                    if (!wasActive) changed = true;
                } else {
                    link.classList.remove('active');
                }
            });

            if (changed) {
                centerActiveNavLink();
            }
        });

        // Center active link when direct clicked
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                if (link.getAttribute('href') === '#contact') {
                    e.preventDefault();
                    openModal();
                    return;
                }
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                centerActiveNavLink();
            });
        });

        // Center active link on initial load
        window.addEventListener('load', centerActiveNavLink);

        // Contact Form Validation and Submission Handling
        const form = document.getElementById('contact-form');
        const successBlock = document.getElementById('form-success');
        const formTitle = document.getElementById('form-section-title');
        const resetBtn = document.getElementById('success-reset-btn');

        const fields = {
            fullname: {
                input: document.getElementById('fullname'),
                validate: (val) => val.trim().length >= 2,
                dirty: false
            },
            email: {
                input: document.getElementById('email'),
                validate: (val) => {
                    const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                    return re.test(val.trim());
                },
                dirty: false
            },
            message: {
                input: document.getElementById('message'),
                validate: (val) => val.trim().length >= 10,
                dirty: false
            }
        };

        function validateField(fieldKey) {
            const field = fields[fieldKey];
            const value = field.input.value;
            const parent = field.input.parentElement;

            // Only validate if it's dirty (user has typed or left the input)
            if (!field.dirty) return true;

            const isValid = field.validate(value);
            if (isValid) {
                parent.classList.remove('has-error');
                parent.classList.add('has-success');
            } else {
                parent.classList.remove('has-success');
                parent.classList.add('has-error');
            }
            return isValid;
        }

        // Attach listeners for real-time validation
        Object.keys(fields).forEach(key => {
            const field = fields[key];

            // Mark field as dirty on input and validate
            field.input.addEventListener('input', () => {
                field.dirty = true;
                validateField(key);
            });

            // Mark field as dirty on blur and validate
            field.input.addEventListener('blur', () => {
                field.dirty = true;
                validateField(key);
            });
        });

        // Submit listener
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Mark all fields as dirty to trigger validation messages
            let isFormValid = true;
            Object.keys(fields).forEach(key => {
                fields[key].dirty = true;
                const isValid = validateField(key);
                if (!isValid) {
                    isFormValid = false;
                }
            });

            if (isFormValid) {
                const submitBtn = form.querySelector('.submit-btn');
                const btnText = submitBtn.querySelector('span');
                const originalText = btnText.textContent;

                // Show loading state
                submitBtn.disabled = true;
                btnText.textContent = 'SENDING...';

                const formData = new FormData(form);

                fetch('index.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        submitBtn.disabled = false;
                        btnText.textContent = originalText;

                        if (data.status === 'success') {
                            // Hide the form and form section title, show success screen
                            form.style.display = 'none';
                            formTitle.style.display = 'none';
                            successBlock.style.display = 'flex';

                            // Automatically reset and show the form again after 20 seconds
                            setTimeout(() => {
                                if (successBlock.style.display === 'flex') {
                                    resetBtn.click();
                                }
                            }, 20000);
                        } else {
                            // Display error message nicely
                            alert(data.message || 'An error occurred. Please try again.');
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        btnText.textContent = originalText;
                        console.error('Submission error:', error);
                        alert('Could not submit form. Please verify local database connectivity.');
                    });
            }
        });

        // Reset button listener to send another message
        resetBtn.addEventListener('click', () => {
            // Reset fields
            Object.keys(fields).forEach(key => {
                const field = fields[key];
                field.input.value = '';
                field.dirty = false;

                const parent = field.input.parentElement;
                parent.classList.remove('has-error');
                parent.classList.remove('has-success');
            });

            // Reset visibility
            successBlock.style.display = 'none';
            form.style.display = 'flex';
            formTitle.style.display = 'block';
        });

        // Modal open/close logic
        const contactModal = document.getElementById('contact-modal');
        const openModalButtons = [
            document.getElementById('open-consultation-btn'),
            ...document.querySelectorAll('.open-modal-btn')
        ];
        const closeModalBtn = document.getElementById('modal-close-btn');

        function openModal(e) {
            if (e) e.preventDefault();
            contactModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            contactModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        openModalButtons.forEach(btn => {
            if (btn) {
                btn.addEventListener('click', openModal);
            }
        });

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        // Close on overlay backdrop click
        contactModal.addEventListener('click', (e) => {
            if (e.target === contactModal) {
                closeModal();
            }
        });

        // Close on Escape key press
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && contactModal.classList.contains('active')) {
                closeModal();
            }
        });
    </script>
</body>

</html>