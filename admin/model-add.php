<?php
// admin/model-add.php - Add a new escort model

// ---------------------------------------------------------------
// STEP 1: Load DB/session/auth WITHOUT printing any HTML.
// (header.php prints HTML, so it must be included AFTER the
//  redirect logic below.)
// ---------------------------------------------------------------
require_once __DIR__ . '/../includes/db.php';
check_admin_auth();

$pageTitle = 'Add New Escort';
$db = getDB();
$error = '';
$defaultGender = in_array($_GET['gender'] ?? '', ['girl', 'boy']) ? $_GET['gender'] : 'girl';

// ---------------------------------------------------------------
// STEP 2: Handle the form submission (redirect happens here,
// before any output has been sent).
// ---------------------------------------------------------------
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

    $main_image = '';
    $gallery = [];

    if (empty($name)) {
        $error = 'Escort Name is required.';
    } else {
        $uploadDir = dirname(__DIR__) . '/uploads/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // 1. Handle Main Image File Upload
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
        }

        // Fallback to text input URL if no file uploaded
        if (empty($main_image) && !empty($_POST['main_image_url'])) {
            $main_image = trim($_POST['main_image_url']);
        }

        if (empty($main_image)) {
            // Default placeholder if none provided
            $main_image = 'images/placeholder.jpg';
        }

        // 2. Handle Multiple Gallery Files Upload
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

        // Add extra gallery URLs from textarea if provided
        if (!empty($_POST['gallery_urls'])) {
            $extraUrls = array_filter(array_map('trim', explode("\n", $_POST['gallery_urls'])));
            foreach ($extraUrls as $url) {
                if (!empty($url)) {
                    $gallery[] = $url;
                }
            }
        }

        // If gallery is empty, use main image as first gallery item
        if (empty($gallery)) {
            $gallery[] = $main_image;
        }

        if (empty($error)) {
            $stmt = $db->prepare("
                INSERT INTO models (name, gender, age, height, weight, breast_size, hair, bio, main_image, gallery_images, whatsapp, telegram, status, views_count, created_at)
                VALUES (:name, :gender, :age, :height, :weight, :breast_size, :hair, :bio, :main_image, :gallery_images, :whatsapp, :telegram, :status, 0, NOW())
            ");

            $stmt->execute([
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
                'status' => $status
            ]);

            $_SESSION['success'] = "Escort model '{$name}' created successfully!";
            header("Location: models.php?gender=" . $gender);
            exit;
        }
    }
}

// ---------------------------------------------------------------
// STEP 3: Only NOW output the layout (sidebar, topbar, etc.)
// ---------------------------------------------------------------
require_once __DIR__ . '/header.php';
?>

<div class="content-card" style="max-width: 900px; margin: 0 auto 40px;">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-plus-circle" style="color: var(--primary);"></i>
            <span>Add New Escort</span>
        </div>
        <a href="models.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Back to Models</a>
    </div>

    <?php if (!empty($error)): ?>
        <div style="padding: 20px 24px 0;">
            <div class="alert alert-error"><?= e($error) ?></div>
        </div>
    <?php endif; ?>

    <form action="model-add.php" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <!-- Name -->
            <div class="form-group">
                <label class="form-label" for="name">Escort Name <span style="color: var(--danger);">*</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. EVA, MILANA, ALEX" required value="<?= e($_POST['name'] ?? '') ?>">
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label class="form-label" for="gender">Category (Gender) <span style="color: var(--danger);">*</span></label>
                <?php $selectedGender = $_POST['gender'] ?? $defaultGender; ?>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="girl" <?= ($selectedGender === 'girl') ? 'selected' : '' ?>>Girl Escort</option>
                    <option value="boy" <?= ($selectedGender === 'boy') ? 'selected' : '' ?>>Boy Escort</option>
                </select>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label class="form-label" for="age">Age</label>
                <input type="number" id="age" name="age" class="form-control" min="18" max="70" value="<?= e($_POST['age'] ?? '24') ?>">
            </div>

            <!-- Height -->
            <div class="form-group">
                <label class="form-label" for="height">Height</label>
                <input type="text" id="height" name="height" class="form-control" placeholder="e.g. 172 cm" value="<?= e($_POST['height'] ?? '170 cm') ?>">
            </div>

            <!-- Weight -->
            <div class="form-group">
                <label class="form-label" for="weight">Weight</label>
                <input type="text" id="weight" name="weight" class="form-control" placeholder="e.g. 54 kg" value="<?= e($_POST['weight'] ?? '54 kg') ?>">
            </div>

            <!-- Breast Size / Chest -->
            <div class="form-group">
                <label class="form-label" for="breast_size">Breast Size / Chest</label>
                <input type="text" id="breast_size" name="breast_size" class="form-control" placeholder="e.g. 34C or Athletic" value="<?= e($_POST['breast_size'] ?? '34B') ?>">
            </div>

            <!-- Hair -->
            <div class="form-group">
                <label class="form-label" for="hair">Hair Color</label>
                <input type="text" id="hair" name="hair" class="form-control" placeholder="e.g. Blonde, Brunette, Black" value="<?= e($_POST['hair'] ?? 'Brunette') ?>">
            </div>

            <!-- Status (Active / Inactive) -->
            <div class="form-group">
                <label class="form-label" for="status">Publication Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="active" <?= (!isset($_POST['status']) || $_POST['status'] === 'active') ? 'selected' : '' ?>>Active (Visible on website)</option>
                    <option value="inactive" <?= (isset($_POST['status']) && $_POST['status'] === 'inactive') ? 'selected' : '' ?>>Inactive (Hidden)</option>
                </select>
            </div>

            <!-- WhatsApp -->
            <div class="form-group">
                <label class="form-label" for="whatsapp">WhatsApp Booking Number</label>
                <input type="text" id="whatsapp" name="whatsapp" class="form-control" placeholder="+1234567890" value="<?= e($_POST['whatsapp'] ?? '+1 234 567 8900') ?>">
            </div>

            <!-- Telegram -->
            <div class="form-group">
                <label class="form-label" for="telegram">Telegram Link or Username</label>
                <input type="text" id="telegram" name="telegram" class="form-control" placeholder="@username or https://t.me/..." value="<?= e($_POST['telegram'] ?? '') ?>">
            </div>

            <!-- Main Image Upload -->
            <div class="form-group full-width" style="background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
                <label class="form-label" style="font-size: 14px; margin-bottom: 8px;">Main Profile Image (Cover Card)</label>
                <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <input type="file" name="main_image_file" class="form-control" accept="image/*" onchange="previewImage(this, 'mainImgPreview')">
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">Upload image directly from your computer (.jpg, .png, .webp)</small>
                        <div style="margin-top: 10px;">
                            <label class="form-label" style="font-size: 12px; color: var(--text-muted);">OR specify image path/URL:</label>
                            <input type="text" name="main_image_url" class="form-control" placeholder="images/1.webp or https://..." value="<?= e($_POST['main_image_url'] ?? '') ?>">
                        </div>
                    </div>
                    <div>
                        <img id="mainImgPreview" src="../images/placeholder.jpg" alt="Preview" style="width: 110px; height: 140px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color); display: block; background: #000;">
                    </div>
                </div>
            </div>

            <!-- Gallery Images Upload -->
            <div class="form-group full-width" style="background: rgba(255, 255, 255, 0.02); padding: 18px; border-radius: var(--radius-sm); border: 1px dashed var(--border-color);">
                <label class="form-label" style="font-size: 14px; margin-bottom: 8px;">Gallery Carousel Images (Profile Slider)</label>
                <input type="file" name="gallery_files[]" class="form-control" accept="image/*" multiple>
                <small style="color: var(--text-muted); display: block; margin-top: 6px;">Select multiple files at once using Ctrl/Cmd to upload a gallery carousel.</small>

                <div style="margin-top: 14px;">
                    <label class="form-label" style="font-size: 12px; color: var(--text-muted);">OR enter image URLs / paths (one per line):</label>
                    <textarea name="gallery_urls" class="form-control" rows="3" placeholder="images/1.webp&#10;images/2.webp&#10;images/3.webp"><?= e($_POST['gallery_urls'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Bio / About -->
            <div class="form-group full-width">
                <label class="form-label" for="bio">Bio & Description</label>
                <textarea id="bio" name="bio" class="form-control" placeholder="Write escort presentation, services, languages spoken..."><?= e($_POST['bio'] ?? '') ?></textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="form-group full-width" style="display: flex; gap: 12px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 15px;">
                    <i class="fa-solid fa-check"></i> Save & Publish Escort
                </button>
                <a href="models.php" class="btn btn-secondary" style="padding: 12px 20px;">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
// Live preview for the main image file input
function previewImage(input, imgId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById(imgId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>