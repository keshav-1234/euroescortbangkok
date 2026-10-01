<?php
// admin/model-status.php - Toggle model active/inactive
require_once __DIR__ . '/../includes/db.php';
check_admin_auth();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$explicitStatus = $_GET['status'] ?? null;
$isAjax = !empty($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

if ($id <= 0) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }
    $_SESSION['error'] = 'Invalid model ID.';
    header("Location: models.php");
    exit;
}

$db = getDB();
$stmt = $db->prepare("SELECT id, status, name FROM models WHERE id = :id");
$stmt->execute(['id' => $id]);
$model = $stmt->fetch();

if (!$model) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Model not found']);
        exit;
    }
    $_SESSION['error'] = 'Model not found.';
    header("Location: models.php");
    exit;
}

if ($explicitStatus && in_array($explicitStatus, ['active', 'inactive'])) {
    $newStatus = $explicitStatus;
} else {
    $newStatus = ($model['status'] === 'active') ? 'inactive' : 'active';
}

$updateStmt = $db->prepare("UPDATE models SET status = :status WHERE id = :id");
$updateStmt->execute(['status' => $newStatus, 'id' => $id]);

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'id' => $id,
        'new_status' => $newStatus,
        'message' => 'Status updated to ' . $newStatus
    ]);
    exit;
}

$_SESSION['success'] = "Status of '{$model['name']}' updated to " . ucfirst($newStatus) . ".";
$redirect = $_SERVER['HTTP_REFERER'] ?? 'models.php';
header("Location: " . $redirect);
exit;
