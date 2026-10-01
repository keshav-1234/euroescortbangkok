<?php
// admin/model-edit.php - Edit an escort model
$pageTitle = 'Edit Escort';
require_once __DIR__ . '/header.php';

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
    $gallery = $existingGallery;

    if (empty($name)) {
        $error = 'Escort Name is required.';
    } else {
        $uploadDir = dirname(__DIR__) . '/uploads/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // 1. Handle Main Image Replacement
        if (!empty($_FILES['main_image_file']['name']) && $_FILES['main_image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['main_image_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
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

        // 2. Handle Gallery Images
        // If user edited the textarea with URLs:
        if (isset($_POST['gallery_urls'])) {
            $urls = array_filter(array_map('trim', explode("\n", $_POST['gallery_urls'])));
            $gallery = array_values($urls);
        }

        // Upload additional gallery files
        if (!empty($_FILES['gallery_files']['name'][0])) {
            $fileCount = count($_FILES['gallery_files']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['gallery_files']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['gallery_files']['name'][$i], PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
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

            $_SESSION['success'] = "Escort '{$name}' updated successfully!";
            header("Location: models.php?gender=" . $gender);
            exit;
        }
    }
}
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

            <!-- Status (Active / Inactive) -->
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

            <!-- Main Image Upload -->
            <div class="form-group full-width" style="background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
                <label class="form-label" style="font-size: 14px; margin-bottom: 8px;">Main Profile Image (Cover Card)</label>
                <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <input type="file" name="main_image_file" class="form-control" accept="image/*" onchange="previewImage(this, 'mainImgPreview')">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Upload a new image to replace current photo.</small>
                        <div style="margin-top: 10px;">
                            <label class="form-label" style="font-size: 12px; color: var(--text-muted);">Current image path / URL:</label>
                            <input type="text" name="main_image_url" class="form-control" value="<?= e($model['main_image']) ?>">
                        </div>
                    </div>
                    <div>
                        <img id="mainImgPreview" src="<?= e(get_image_url($model['main_image'], true)) ?>" alt="<?= e($model['name']) ?>" style="width: 110px; height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color); display: block; background: #000;">
                    </div>
                </div>
            </div>

            <!-- Gallery Images Upload -->
            <div class="form-group full-width" style="background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
                <label class="form-label" style="font-size: 14px; margin-bottom: 8px;">Gallery Carousel Images (Profile Slider)</label>
                
                <?php if (!empty($existingGallery)): ?>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 14px;">
                        <?php foreach ($existingGallery as $gImg): ?>
                            <img src="<?= e(get_image_url($gImg, true)) ?>" alt="Gallery" style="width: 60px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color);">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <label class="form-label" style="font-size: 12px; color: var(--text-muted);">Upload additional gallery images:</label>
                <input type="file" name="gallery_files[]" class="form-control" accept="image/*" multiple>
                
                <div style="margin-top: 14px;">
                    <label class="form-label" style="font-size: 12px; color: var(--text-muted);">Gallery URLs (one per line):</label>
                    <textarea name="gallery_urls" class="form-control" rows="4"><?= e(implode("\n", $existingGallery)) ?></textarea>
                </div>
            </div>

            <!-- Bio / About -->
            <div class="form-group full-width">
                <label class="form-label" for="bio">Bio & Description</label>
                <textarea id="bio" name="bio" class="form-control"><?= e($model['bio'] ?? '') ?></textarea>
            </div>

            <!-- Submit Buttons -->
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

<?php require_once __DIR__ . '/footer.php'; ?>
