<?php
// admin/index.php
$pageTitle = 'Dashboard & Statistics';
require_once __DIR__ . '/header.php';

$db = getDB();

// 1. Overall Statistics
$totalModels = $db->query("SELECT COUNT(*) FROM models")->fetchColumn();
$totalGirls = $db->query("SELECT COUNT(*) FROM models WHERE gender = 'girl'")->fetchColumn();
$totalBoys = $db->query("SELECT COUNT(*) FROM models WHERE gender = 'boy'")->fetchColumn();
$totalActive = $db->query("SELECT COUNT(*) FROM models WHERE status = 'active'")->fetchColumn();
$totalInactive = $db->query("SELECT COUNT(*) FROM models WHERE status = 'inactive'")->fetchColumn();
$totalViews = $db->query("SELECT COALESCE(SUM(views_count), 0) FROM models")->fetchColumn();

// 2. Top Viewed Escorts
$stmtTop = $db->query("SELECT * FROM models ORDER BY views_count DESC, id DESC LIMIT 5");
$topModels = $stmtTop->fetchAll();

// 3. Recently Added Escorts
$stmtRecent = $db->query("SELECT * FROM models ORDER BY id DESC LIMIT 5");
$recentModels = $stmtRecent->fetchAll();
?>

<!-- Stat Cards Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Escorts</div>
            <div class="stat-value"><?= number_format($totalModels) ?></div>
        </div>
        <div class="stat-icon gold">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Girls Escorts</div>
            <div class="stat-value"><?= number_format($totalGirls) ?></div>
        </div>
        <div class="stat-icon pink">
            <i class="fa-solid fa-person-dress"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Boys Escorts</div>
            <div class="stat-value"><?= number_format($totalBoys) ?></div>
        </div>
        <div class="stat-icon blue">
            <i class="fa-solid fa-person"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Total Views</div>
            <div class="stat-value"><?= number_format($totalViews) ?></div>
        </div>
        <div class="stat-icon gold">
            <i class="fa-solid fa-fire"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Active on Site</div>
            <div class="stat-value"><?= number_format($totalActive) ?></div>
        </div>
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-label">Inactive (Hidden)</div>
            <div class="stat-value"><?= number_format($totalInactive) ?></div>
        </div>
        <div class="stat-icon" style="background: rgba(235, 87, 87, 0.15); color: var(--danger);">
            <i class="fa-solid fa-eye-slash"></i>
        </div>
    </div>
</div>

<!-- Quick Actions Banner -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 20px 24px; margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 4px;">Quick Actions</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Quickly manage your models and website visibility</p>
    </div>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="model-add.php?gender=girl" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add New Girl
        </a>
        <a href="model-add.php?gender=boy" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-plus"></i> Add New Boy
        </a>
        <a href="models.php" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-list-check"></i> Manage All Models
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px;">
    <!-- Top Viewed Models -->
    <div class="content-card" style="margin-bottom: 0;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-chart-line" style="color: var(--primary);"></i>
                <span>Top Viewed Escorts</span>
            </div>
            <a href="models.php" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Category</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($topModels)): ?>
                        <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">No models found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($topModels as $m): ?>
                            <tr>
                                <td>
                                    <div class="model-cell">
                                        <img src="<?= e(get_image_url($m['main_image'], true)) ?>" alt="<?= e($m['name']) ?>" class="model-thumb">
                                        <div>
                                            <span class="model-name"><?= e($m['name']) ?></span>
                                            <small style="color: var(--text-muted);"><?= e($m['age'] ?? '24') ?> y/o • <?= e($m['height'] ?? '168 cm') ?></small>
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
                                    <strong style="color: var(--primary);"><i class="fa-solid fa-eye"></i> <?= number_format($m['views_count']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge <?= $m['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                        <?= ucfirst($m['status']) ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="model-edit.php?id=<?= $m['id'] ?>" class="btn btn-secondary btn-sm" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recently Added Models -->
    <div class="content-card" style="margin-bottom: 0;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-clock-rotate-left" style="color: var(--primary);"></i>
                <span>Recently Added Escorts</span>
            </div>
            <a href="model-add.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Add New</a>
        </div>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentModels)): ?>
                        <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">No models found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentModels as $m): ?>
                            <tr>
                                <td>
                                    <div class="model-cell">
                                        <img src="<?= e(get_image_url($m['main_image'], true)) ?>" alt="<?= e($m['name']) ?>" class="model-thumb">
                                        <div>
                                            <span class="model-name"><?= e($m['name']) ?></span>
                                            <small style="color: var(--text-muted);"><?= e($m['age'] ?? '24') ?> y/o</small>
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
                                    <small style="color: var(--text-muted);"><?= !empty($m['created_at']) ? date('M d, Y', strtotime($m['created_at'])) : 'N/A' ?></small>
                                </td>
                                <td>
                                    <span class="badge <?= $m['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                        <?= ucfirst($m['status']) ?>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <a href="model-edit.php?id=<?= $m['id'] ?>" class="btn btn-secondary btn-sm" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
