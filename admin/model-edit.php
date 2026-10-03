<?php
// admin/model-edit.php - Edit an escort model

// STEP 1: Load DB/session/auth WITHOUT printing HTML (header.php prints HTML,
// so it is included only after all redirect logic).
require_once __DIR__ . '/../includes/db.php';
check_admin_auth();

$pageTitle = 'Edit Escort';
$db = getDB();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error'] = 'Invalid model ID.';
    header("Location: models.php");
    exit;
}

$stmt = $db->prepare("SELECT * FROM models WHERE id = :id");
$stmt->execute(['id' => $id]);
$model = $stmt->fetch();

if (!$model) {
    $_SESSION['error'] = 'Model not found.';
    header("Location: models.php");
    exit;
}

$error = '';
$existingGallery = !empty($model['gallery_images']) ? json_decode($model['gallery_images'], true) : [];
if (!is_array($existingGallery)) {
    $existingGallery = [];
}

// STEP 2: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $gender = in_array($_POST['gender'] ?? '', ['girl', 'boy']) ? $_POST['gender'] : 'girl';
    $age = !empty($_POST['age']) ? (int)$_POST['age'] : 24;
    $height = trim($_POST['height'] ?? '168 cm');
    $weight = trim($_POST['weight'] ?? '54 kg');
    $breast_size = trim($_POST['breast_size'] ?? '34B');
    $hair = trim($_POST['hair'] ?? 'Brunette');
    $bio = trim($_POST['bio'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $telegram = trim($_POST['telegram'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';

    $main_image = $model['main_image'];
    $gallery = [];

    // Main image removed via the ✕ button
    $remove_main = ($_POST['remove_main_image'] ?? '0') === '1';
    if ($remove_main) {
        $main_image = 'images/placeholder.jpg';
    }

    if (empty($name)) {
        $error = 'Escort Name is required.';
    } else {
        $uploadDir = dirname(__DIR__) . '/uploads/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        // 1. Main image replacement (file upload, then URL; both override the removal flag)
        if (!empty($_FILES['main_image_file']['name']) && $_FILES['main_image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['main_image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $newFilename = 'model_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $targetPath = $uploadDir . $newFilename;
                if (move_uploaded_file($_FILES['main_image_file']['tmp_name'], $targetPath)) {
                    $main_image = 'uploads/' . $newFilename;
                }
            } else {
                $error = 'Main Image must be a valid image format (jpg, png, webp).';
            }
        } elseif (!empty($_POST['main_image_url'])) {
            $main_image = trim($_POST['main_image_url']);
        }

        // 2. Gallery: keep only existing images that were not removed
        //    (whitelisted against what is stored in the DB)
        $kept = (isset($_POST['existing_gallery']) && is_array($_POST['existing_gallery']))
            ? array_values(array_intersect($_POST['existing_gallery'], $existingGallery))
            : [];
        $gallery = $kept;

        // Extra URLs (textarea is for NEW urls only)
        if (!empty($_POST['gallery_urls'])) {
            foreach (array_filter(array_map('trim', explode("\n", $_POST['gallery_urls']))) as $u) {
                $gallery[] = $u;
            }
        }

        // Newly uploaded gallery files
        if (!empty($_FILES['gallery_files']['name'][0])) {
            $fileCount = count($_FILES['gallery_files']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['gallery_files']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['gallery_files']['name'][$i], PATHINFO_EXTENSION));
                    if (in_array($ext, $allowed)) {
                        $newFilename = 'gallery_' . time() . '_' . $i . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
                        $targetPath = $uploadDir . $newFilename;
                        if (move_uploaded_file($_FILES['gallery_files']['tmp_name'][$i], $targetPath)) {
                            $gallery[] = 'uploads/' . $newFilename;
                        }
                    }
                }
            }
        }

        if (empty($gallery)) {
            $gallery = [$main_image];
        }

        if (empty($error)) {
            $updateStmt = $db->prepare("
                UPDATE models SET
                    name = :name,
                    gender = :gender,
                    age = :age,
                    height = :height,
                    weight = :weight,
                    breast_size = :breast_size,
                    hair = :hair,
                    bio = :bio,
                    main_image = :main_image,
                    gallery_images = :gallery_images,
                    whatsapp = :whatsapp,
                    telegram = :telegram,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id
            ");

            $updateStmt->execute([
                'name' => $name,
                'gender' => $gender,
                'age' => $age,
                'height' => $height,
                'weight' => $weight,
                'breast_size' => $breast_size,
                'hair' => $hair,
                'bio' => $bio,
                'main_image' => $main_image,
                'gallery_images' => json_encode($gallery),
                'whatsapp' => $whatsapp,
                'telegram' => $telegram,
                'status' => $status,
                'id' => $id
            ]);

            // Delete removed uploads from disk (only files inside uploads/, and no longer used)
            $stillUsed = array_merge($gallery, [$main_image]);
            $toDelete  = array_diff(array_merge($existingGallery, [$model['main_image']]), $stillUsed);
            foreach ($toDelete as $p) {
                if (is_string($p) && strpos($p, 'uploads/') === 0 && basename($p) === substr($p, 8)) {
                    @unlink(dirname(__DIR__) . '/' . $p);
                }
            }

            $_SESSION['success'] = "Escort '{$name}' updated successfully!";
            header("Location: models.php?gender=" . $gender);
            exit;
        }
    }
}

// STEP 3: Only now output the layout
require_once __DIR__ . '/header.php';
?>

<div class="content-card" style="max-width: 900px; margin: 0 auto 40px;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-pen-to-square" style="color: var(--primary);"></i>
            <span>Edit Escort: <?= e($model['name']) ?> (ID #<?= $model['id'] ?>)</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="../profile/?id=<?= $model['id'] ?>" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> View Profile
            </a>
            <a href="models.php" class="btn btn-secondary btn-sm">Back to Models</a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div style="padding: 20px 24px 0;">
            <div class="alert alert-error"><?= e($error) ?></div>
        </div>
    <?php endif; ?>

    <form action="model-edit.php?id=<?= $model['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <!-- Name -->
            <div class="form-group">
                <label class="form-label" for="name">Escort Name <span style="color: var(--danger);">*</span></label>
                <input type="text" id="name" name="name" class="form-control" required value="<?= e($model['name']) ?>">
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label class="form-label" for="gender">Category (Gender) <span style="color: var(--danger);">*</span></label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="girl" <?= $model['gender'] === 'girl' ? 'selected' : '' ?>>Girl Escort</option>
                    <option value="boy" <?= $model['gender'] === 'boy' ? 'selected' : '' ?>>Boy Escort</option>
                </select>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label class="form-label" for="age">Age</label>
                <input type="number" id="age" name="age" class="form-control" min="18" max="70" value="<?= e($model['age'] ?? '24') ?>">
            </div>

            <!-- Height -->
            <div class="form-group">
                <label class="form-label" for="height">Height</label>
                <input type="text" id="height" name="height" class="form-control" value="<?= e($model['height'] ?? '168 cm') ?>">
            </div>

            <!-- Weight -->
            <div class="form-group">
                <label class="form-label" for="weight">Weight</label>
                <input type="text" id="weight" name="weight" class="form-control" value="<?= e($model['weight'] ?? '54 kg') ?>">
            </div>

            <!-- Breast Size / Chest -->
            <div class="form-group">
                <label class="form-label" for="breast_size">Breast Size / Chest</label>
                <input type="text" id="breast_size" name="breast_size" class="form-control" value="<?= e($model['breast_size'] ?? '34B') ?>">
            </div>

            <!-- Hair -->
            <div class="form-group">
                <label class="form-label" for="hair">Hair Color</label>
                <input type="text" id="hair" name="hair" class="form-control" value="<?= e($model['hair'] ?? 'Brunette') ?>">
            </div>

            <!-- Status -->
            <div class="form-group">
                <label class="form-label" for="status">Publication Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" <?= $model['status'] === 'active' ? 'selected' : '' ?>>Active (Visible on website)</option>
                    <option value="inactive" <?= $model['status'] === 'inactive' ? 'selected' : '' ?>>Inactive (Hidden)</option>
                </select>
            </div>

            <!-- WhatsApp -->
            <div class="form-group">
                <label class="form-label" for="whatsapp">WhatsApp Booking Number</label>
                <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="<?= e($model['whatsapp'] ?? '') ?>">
            </div>

            <!-- Telegram -->
            <div class="form-group">
                <label class="form-label" for="telegram">Telegram Link or Username</label>
                <input type="text" id="telegram" name="telegram" class="form-control" value="<?= e($model['telegram'] ?? '') ?>">
            </div>

            <!-- Main Image -->
            <div class="form-group full-width" style="background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
                <label class="form-label" style="font-size: 14px; margin-bottom: 8px;">Main Profile Image (Cover Card)</label>
                <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <input type="file" name="main_image_file" class="form-control" accept="image/*" onchange="previewMainImage(this)">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Upload a new image to replace current photo.</small>
                        <div style="margin-top: 10px;">
                            <label class="form-label" style="font-size: 12px; color: var(--text-muted);">Current image path / URL:</label>
                            <input type="text" name="main_image_url" class="form-control" value="<?= e($model['main_image']) ?>">
                        </div>
                    </div>
                    <div>
                        <div class="thumb-wrap">
                            <img id="mainImgPreview" data-placeholder="../images/placeholder.jpg"
                                 src="<?= e(get_image_url($model['main_image'], true)) ?>"
                                 alt="<?= e($model['name']) ?>" style="width:110px;height:140px;">
                            <button type="button" id="mainRemoveBtn" class="thumb-remove" title="Remove image" onclick="removeMainImage()">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <input type="hidden" name="remove_main_image" id="removeMainImage" value="0">
                    </div>
                </div>
            </div>

            <!-- Gallery Images -->
            <div class="form-group full-width" style="background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
                <label class="form-label" style="font-size: 14px; margin-bottom: 8px;">Gallery Carousel Images (Profile Slider)</label>

                <?php if (!empty($existingGallery)): ?>
                    <div class="thumb-grid">
                        <?php foreach ($existingGallery as $gImg): ?>
                            <div class="thumb-wrap">
                                <img src="<?= e(get_image_url($gImg, true)) ?>" alt="Gallery" style="width:60px;height:75px;">
                                <input type="hidden" name="existing_gallery[]" value="<?= e($gImg) ?>">
                                <button type="button" class="thumb-remove" title="Remove" onclick="removeExistingGallery(this)">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <label class="form-label" style="font-size: 12px; color: var(--text-muted);">Upload additional gallery images:</label>
                <input type="file" name="gallery_files[]" class="form-control" accept="image/*" multiple onchange="onGalleryFilesChange(this)">
                <div id="newGalleryPreview" class="thumb-grid" style="margin-top:12px;"></div>

                <div style="margin-top: 14px;">
                    <label class="form-label" style="font-size: 12px; color: var(--text-muted);">Add more URLs (one per line):</label>
                    <textarea name="gallery_urls" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <!-- Bio -->
            <div class="form-group full-width">
                <label class="form-label" for="bio">Bio & Description</label>
                <textarea id="bio" name="bio" class="form-control"><?= e($model['bio'] ?? '') ?></textarea>
            </div>

            <!-- Buttons -->
            <div class="form-group full-width" style="display: flex; gap: 12px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 15px;">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
                <a href="models.php" class="btn btn-secondary" style="padding: 12px 20px;">Cancel</a>
                <a href="model-delete.php?id=<?= $model['id'] ?>" onclick="return confirmDelete('<?= addslashes(e($model['name'])) ?>')" class="btn btn-danger" style="margin-left: auto; padding: 12px 20px;">
                    <i class="fa-solid fa-trash"></i> Delete Model
                </a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/image-manager.php'; ?>
<?php require_once __DIR__ . '/footer.php'; ?>