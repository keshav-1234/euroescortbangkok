<?php // admin/image-manager.php - shared thumbnail + remove (✕) logic for model-add.php and model-edit.php ?>
<style>
.thumb-wrap { position: relative; display: inline-block; }
.thumb-wrap img {
    display: block; object-fit: cover; border-radius: 6px;
    border: 1px solid var(--border-color); background: #000;
}
.thumb-remove {
    position: absolute; top: -8px; right: -8px;
    width: 22px; height: 22px; padding: 0;
    border-radius: 50%; border: 2px solid #1a1a1a;
    background: var(--danger, #e74c3c); color: #fff;
    font-size: 11px; line-height: 1; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.thumb-remove:hover { transform: scale(1.12); }
.thumb-grid { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 14px; }
</style>

<script>
// ---------- MAIN IMAGE ----------
function previewMainImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('mainImgPreview').src = e.target.result;
            document.getElementById('mainRemoveBtn').style.display = 'flex';
            var flag = document.getElementById('removeMainImage');
            if (flag) flag.value = '0';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeMainImage() {
    var img = document.getElementById('mainImgPreview');
    img.src = img.dataset.placeholder;
    var file = document.querySelector('input[name="main_image_file"]');
    if (file) file.value = '';
    var url = document.querySelector('input[name="main_image_url"]');
    if (url) url.value = '';
    var flag = document.getElementById('removeMainImage');
    if (flag) flag.value = '1';
    document.getElementById('mainRemoveBtn').style.display = 'none';
}

// ---------- GALLERY: existing images (edit page) ----------
function removeExistingGallery(btn) {
    // removing the wrapper also removes its hidden existing_gallery[] input
    btn.closest('.thumb-wrap').remove();
}

// ---------- GALLERY: newly selected files ----------
var galleryFiles = [];

function onGalleryFilesChange(input) {
    Array.prototype.forEach.call(input.files, function (f) { galleryFiles.push(f); });
    renderNewGallery(input);
}

function removeNewGallery(i) {
    galleryFiles.splice(i, 1);
    renderNewGallery(document.querySelector('input[name="gallery_files[]"]'));
}

function renderNewGallery(input) {
    // keep the real <input> in sync with what's shown
    var dt = new DataTransfer();
    galleryFiles.forEach(function (f) { dt.items.add(f); });
    input.files = dt.files;

    var box = document.getElementById('newGalleryPreview');
    box.innerHTML = '';
    galleryFiles.forEach(function (f, i) {
        var wrap = document.createElement('div');
        wrap.className = 'thumb-wrap';
        var img = document.createElement('img');
        img.src = URL.createObjectURL(f);
        img.style.cssText = 'width:60px;height:75px;';
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'thumb-remove';
        btn.title = 'Remove';
        btn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        btn.onclick = function () { removeNewGallery(i); };
        wrap.appendChild(img);
        wrap.appendChild(btn);
        box.appendChild(wrap);
    });
}
</script>