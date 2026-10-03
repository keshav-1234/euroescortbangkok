<?php
// includes/db.php - Database connection & global helper functions

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!ob_get_level()) {
    ob_start();
}


define('DB_HOST', 'localhost');
define('DB_NAME', 'escort_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Returns a singleton PDO connection
 */
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Check if database exists, if not create and select
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $pdo->exec("USE `" . DB_NAME . "`;");
            
            // Auto initialize schema if tables don't exist
            initTablesIfMissing($pdo);
            
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }
    return $pdo;
}

/**
 * Auto-creates schema & seeds if missing
 */
function initTablesIfMissing($pdo) {
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'models'")->fetch();
    if (!$tableCheck) {
        $sqlFile = dirname(__DIR__) . '/escort_db.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $pdo->exec($sql);
        }
    }
}

/**
 * Normalizes image URLs for root, subfolders (profile/, admin/)
 * @param string $path Image path stored in DB
 * @param bool $isSubfolder True if called from profile/ or admin/
 * @return string
 */
function get_image_url($path, $isSubfolder = false) {
    if (empty($path)) {
        $placeholder = 'images/Screenshot-2026-05-04-at-4.02.44-PM.png';
        return $isSubfolder ? '../' . $placeholder : './' . $placeholder;
    }
    // External link
    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }
    // Clean leading ./ or /
    $cleanPath = ltrim($path, './\\');
    
    if ($isSubfolder) {
        return '../' . $cleanPath;
    } else {
        return './' . $cleanPath;
    }
}

/**
 * Sanitize output
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Check admin authorization
 */
function check_admin_auth() {
    if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
}
