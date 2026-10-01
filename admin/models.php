<?php
// admin/models.php
$pageTitle = 'Manage Escorts';
require_once __DIR__ . '/header.php';

$db = getDB();

// Filters & Search
$gender = $_GET['gender'] ?? '';
$status = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM models WHERE 1=1";
$params = [];

if ($gender === 'girl' || $gender === 'boy') {
    $sql .= " AND gender = :gender";
    $params['gender'] = $gender;
}

if ($status === 'active' || $status === 'inactive') {
    $sql .= " AND status = :status";
    $params['status'] = $status;
}

if (!empty($search)) {
    $sql .= " AND (name LIKE :search OR bio LIKE :search)";
    $params['search'] = "%{$search}%";
}

$sql .= " ORDER BY id DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$models = $stmt->fetchAll();

// Counts for filter pills
$countAll = $db->query("SELECT COUNT(*) FROM models")->fetchColumn();
$countGirls = $db->query("SELECT COUNT(*) FROM models WHERE gender = 'girl'")->fetchColumn();
$countBoys = $db->query("SELECT COUNT(*) FROM models WHERE gender = 'boy'")->fetchColumn();
$countActive = $db->query("SELECT COUNT(*) FROM models WHERE status = 'active'")->fetchColumn();
$countInactive = $db->query("SELECT COUNT(*) FROM models WHERE status = 'inactive'")->fetchColumn();
?>

<!-- Filter & Search Bar -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <!-- Filter Pills -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="models.php" class="btn btn-sm <?= (empty($gender) && empty($status)) ? 'btn-primary' : 'btn-secondary' ?>">
            All (<?= $countAll ?>)
        </a>
        <a href="models.php?gender=girl" class="btn btn-sm <?= ($gender === 'girl') ? 'btn-primary' : 'btn-secondary' ?>">
            <i class="fa-solid fa-person-dress"></i> Girls (<?= $countGirls ?>)
        </a>
        <a href="models.php?gender=boy" class="btn btn-sm <?= ($gender === 'boy') ? 'btn-primary' : 'btn-secondary' ?>">
            <i class="fa-solid fa-person"></i> Boys (<?= $countBoys ?>)
        </a>
        <a href="models.php?status=active" class="btn btn-sm <?= ($status === 'active') ? 'btn-primary' : 'btn-secondary' ?>">
            <i class="fa-solid fa-circle-check" style="color: var(--success);"></i> Active (<?= $countActive ?>)
        </a>
        <a href="models.php?status=inactive" class="btn btn-sm <?= ($status === 'inactive') ? 'btn-primary' : 'btn-secondary' ?>">
            <i class="fa-solid fa-eye-slash" style="color: var(--danger);"></i> Inactive (<?= $countInactive ?>)
        </a>
    </div>

    <!-- Search Form -->
    <form action="models.php" method="GET" style="display: flex; gap: 10px; align-items: center;">
        <?php if (!empty($gender)): ?>
            <input type="hidden" name="gender" value="<?= e($gender) ?>">
        <?php endif; ?>
        <?php if (!empty($status)): ?>
            <input type="hidden" name="status" value="<?= e($status) ?>">
        <?php endif; ?>
        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="<?= e($search) ?>" style="padding: 7px 12px; font-size: 13px; width: 200px;">
        <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
        <?php if (!empty($search)): ?>
            <a href="models.php<?= !empty($gender) ? '?gender=' . e($gender) : '' ?>" class="btn btn-danger btn-sm" title="Clear Search"><i class="fa-solid fa-xmark"></i></a>
        <?php endif; ?>
    </form>
</div>

<!-- Models Table Card -->
<div class="content-card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-users" style="color: var(--primary);"></i>
            <span>Escort Models List (<?= count($models) ?>)</span>
        </div>
        <div>
            <a href="model-add.php<?= !empty($gender) ? '?gender=' . e($gender) : '' ?>" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Add New Escort
            </a>
        </div>
    </div>

    <div class="data-table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Model Details</th>
                    <th>Gender</th>
                    <th>Specifications</th>
                    <th>Views</th>
                    <th>Status (ON / OFF)</th>
                    <th style="text-align: right; width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($models)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                            <i class="fa-regular fa-folder-open" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                            No escorts found matching your criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($models as $m): ?>
                        <tr>
                            <td style="color: var(--text-muted); font-size: 12px; font-weight: 600;">#<?= $m['id'] ?></td>
                            <td>
                                <div class="model-cell">
                                    <img src="<?= e(get_image_url($m['main_image'], true)) ?>" alt="<?= e($m['name']) ?>" class="model-thumb">
                                    <div>
                                        <a href="model-edit.php?id=<?= $m['id'] ?>" class="model-name" style="font-size: 15px;">
                                            <?= e($m['name']) ?>
                                        </a>
                                        <small style="color: var(--text-muted);">
                                            <?= !empty($m['whatsapp']) ? '<i class="fa-brands fa-whatsapp"></i> ' . e($m['whatsapp']) : 'No phone' ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?= $m['gender'] ?>">
                                    <i class="fa-solid fa-<?= $m['gender'] === 'girl' ? 'person-dress' : 'person' ?>"></i>
                                    <?= ucfirst($m['gender']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px;">
                                    <span><?= e($m['age'] ?? '24') ?> y/o</span> • 
                                    <span><?= e($m['height'] ?? '168 cm') ?></span>
                                    <?php if ($m['gender'] === 'girl' && !empty($m['breast_size'])): ?>
                                        • <span><?= e($m['breast_size']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong style="color: var(--primary);">
                                    <i class="fa-solid fa-eye"></i> <?= number_format($m['views_count']) ?>
                                </strong>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <label class="status-toggle">
                                        <input type="checkbox" class="js-status-toggle" data-id="<?= $m['id'] ?>" <?= $m['status'] === 'active' ? 'checked' : '' ?>>
                                        <span class="slider"></span>
                                    </label>
                                    <span id="status-badge-<?= $m['id'] ?>" class="badge <?= $m['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                        <?= ucfirst($m['status']) ?>
                                    </span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="../profile/?id=<?= $m['id'] ?>" target="_blank" class="btn btn-secondary btn-sm" title="View Profile on Website">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                    <a href="model-edit.php?id=<?= $m['id'] ?>" class="btn btn-secondary btn-sm" title="Edit Model">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="model-delete.php?id=<?= $m['id'] ?>" onclick="return confirmDelete('<?= addslashes(e($m['name'])) ?>')" class="btn btn-danger btn-sm" title="Delete Model">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
