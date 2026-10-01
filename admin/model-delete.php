<?php
// admin/model-delete.php - Delete an escort model
require_once __DIR__ . '/../includes/db.php';
check_admin_auth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error'] = 'Invalid model ID.';
    header("Location: models.php");
    exit;
}

$db = getDB();
$stmt = $db->prepare("SELECT id, name, main_image, gallery_images FROM models WHERE id = :id");
$stmt->execute(['id' => $id]);
$model = $stmt->fetch();

if (!$model) {
    $_SESSION['error'] = 'Model not found.';
    header("Location: models.php");
    exit;
}

// Optionally clean up local uploaded images
$baseDir = dirname(__DIR__);
if (!empty($model['main_image']) && strpos($model['main_image'], 'uploads/') !== false) {
    $filePath = $baseDir . '/' . ltrim($model['main_image'], './\\');
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
}

if (!empty($model['gallery_images'])) {
    $gallery = json_decode($model['gallery_images'], true);
    if (is_array($gallery)) {
        foreach ($gallery as $img) {
            if (strpos($img, 'uploads/') !== false) {
                $filePath = $baseDir . '/' . ltrim($img, './\\');
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }
}

$delStmt = $db->prepare("DELETE FROM models WHERE id = :id");
$delStmt->execute(['id' => $id]);

$_SESSION['success'] = "Escort '{$model['name']}' has been permanently deleted.";
header("Location: models.php");
exit;
