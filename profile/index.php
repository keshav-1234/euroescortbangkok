<?php
// ===================== LOGIC =====================
require_once __DIR__ . '/../includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!function_exists('e')) { function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); } }
if (!function_exists('get_image_url')) {
    function get_image_url($p, $sub = false) {
        $p = (string)$p;
        if ($p === '') return ($sub ? '../' : '') . 'assets/no-image.jpg';
        if (preg_match('#^(https?:)?//#i', $p)) return $p;
        return ($sub ? '../' : '') . ltrim($p, '/');
    }
}
$db = getDB();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    $model = $db->query("SELECT * FROM models WHERE status = 'active' ORDER BY id ASC LIMIT 1")->fetch();
} else {
    $st = $db->prepare("SELECT * FROM models WHERE id = :id AND status = 'active' LIMIT 1");
    $st->execute([':id' => $id]);
    $model = $st->fetch();
}
if (!$model) { header('Location: ../index.php'); exit; }

$vk = 'viewed_model_' . $model['id'];
if (empty($_SESSION[$vk])) {
    $_SESSION[$vk] = true;
    $db->prepare("UPDATE models SET views_count = views_count + 1 WHERE id = :id")->execute([':id' => $model['id']]);
}

$gallery = !empty($model['gallery_images']) ? json_decode($model['gallery_images'], true) : [];
if (!is_array($gallery)) $gallery = [];
$gallery = array_values(array_filter($gallery, fn($g) => is_string($g) && $g !== ''));
if (!$gallery) $gallery = [$model['main_image'] ?? ''];
while (count($gallery) < 4) $gallery = array_merge($gallery, $gallery);

$sm = $db->prepare("SELECT * FROM models WHERE id != :id AND status = 'active' ORDER BY (gender = :gender) DESC, sort_order ASC, RAND() LIMIT 12");
$sm->execute([':id' => $model['id'], ':gender' => $model['gender'] ?? '']);
$moreModels = $sm->fetchAll();

$waRaw = trim((string)($model['whatsapp'] ?? ''));
if ($waRaw !== '' && preg_match('#^https?://#i', $waRaw)) $WA = $waRaw;
else $WA = 'https://wa.me/' . (preg_replace('/\D+/', '', $waRaw) ?: '61489987819') . '?text=' . rawurlencode('Hello, I would like to book ' . $model['name']);
$TG = trim((string)($model['telegram'] ?? '')) ?: '#';
$isBoy = (($model['gender'] ?? '') === 'boy');

// ===================== MARKUP HELPERS (output identical to original HTML) =====================
$SVG_WA  = '<svg aria-hidden="true" class="e-font-icon-svg e-fab-whatsapp" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>';
$SVG_TG  = '<svg aria-hidden="true" class="e-font-icon-svg e-fab-telegram" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z"/></svg>';
$SVG_TGP = '<svg aria-hidden="true" class="e-font-icon-svg e-fab-telegram-plane" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z"/></svg>';
$SVG_ARR = '<svg aria-hidden="true" class="e-font-icon-svg e-fas-long-arrow-alt-right" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z"/></svg>';
$SVG_CL  = '<svg aria-hidden="true" class="e-font-icon-svg e-eicon-chevron-left" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M646 125C629 125 613 133 604 142L308 442C296 454 292 471 292 487 292 504 296 521 308 533L604 854C617 867 629 875 646 875 663 875 679 871 692 858 704 846 713 829 713 812 713 796 708 779 692 767L438 487 692 225C700 217 708 204 708 187 708 171 704 154 692 142 675 129 663 125 646 125Z"/></svg>';
$SVG_CR  = '<svg aria-hidden="true" class="e-font-icon-svg e-eicon-chevron-right" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M696 533C708 521 713 504 713 487 713 471 708 454 696 446L400 146C388 133 375 125 354 125 338 125 325 129 313 142 300 154 292 171 292 187 292 204 296 221 308 233L563 492 304 771C292 783 288 800 288 817 288 833 296 850 308 863 321 871 338 875 354 875 371 875 388 867 400 854L696 533Z"/></svg>';
$LOGO = './images/Screenshot-2026-05-04-at-4.02.44-PM.png';
$LOGO_SRCSET = 'https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM.png 610w, https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM-300x183.png 300w';

$TX = '{&quot;_transform_translateX_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:40,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:19,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}';
$BG = '{&quot;background_background&quot;:&quot;classic&quot;}';

function con($id, $cls, $bg = true) { global $BG; return '<div class="elementor-element elementor-element-'.$id.' '.$cls.'" data-id="'.$id.'" data-element_type="container" data-e-type="container"'.($bg?' data-settings="'.$BG.'"':'').'>'; }
function parent_con($id, $extra, $bg = true) { return con($id, $extra.' e-flex e-con-boxed e-con e-parent e-lazyloaded', $bg).'<div class="e-con-inner">'; }
function child_con($id, $bg = false) { return con($id, 'e-con-full e-flex e-con e-child', $bg); }
function widget($id, $cls, $type, $inner, $settings = '') {
    return '<div class="elementor-element elementor-element-'.$id.' '.$cls.' elementor-widget elementor-widget-'.$type.'" data-id="'.$id.'" data-element_type="widget" data-e-type="widget"'.($settings?' data-settings="'.$settings.'"':'').' data-widget_type="'.$type.'.default"><div class="elementor-widget-container">'.$inner.'</div></div>';
}
function w_heading($id, $html) { return widget($id, '', 'heading', '<h2 class="elementor-heading-title elementor-size-default">'.$html.'</h2>'); }
function w_btn($id, $href, $label, $icon = '', $cls = '', $settings = '', $blank = false) {
    return widget($id, $cls, 'button', '<div class="elementor-button-wrapper"><a class="elementor-button elementor-button-link elementor-size-sm" href="'.e($href).'"'.($blank?' target="_blank" rel="noopener"':'').'><span class="elementor-button-content-wrapper">'.($icon?'<span class="elementor-button-icon">'.$icon.'</span>':'').'<span class="elementor-button-text">'.$label.'</span></span></a></div>', $settings);
}
function w_spacer($id) { return widget($id, '', 'spacer', '<div class="elementor-spacer"><div class="elementor-spacer-inner"></div></div>'); }
function w_anchor($id, $anchor) { return widget($id, '', 'menu-anchor', '<div class="elementor-menu-anchor" id="'.$anchor.'"></div>'); }
function w_image($id, $img) { return widget($id, '', 'image', $img); }
function w_social($id, $cls, $r1, $r2) {
    global $WA, $TG, $SVG_WA, $SVG_TG;
    $in = '<div class="elementor-social-icons-wrapper elementor-grid" role="list">'
        .'<span class="elementor-grid-item" role="listitem"><a class="elementor-icon elementor-social-icon elementor-social-icon-whatsapp elementor-repeater-item-'.$r1.'" href="'.e($WA).'" target="_blank" rel="noopener"><span class="elementor-screen-only">Whatsapp</span>'.$SVG_WA.'</a></span>'
        .'<span class="elementor-grid-item" role="listitem"><a class="elementor-icon elementor-social-icon elementor-social-icon-telegram elementor-repeater-item-'.$r2.'" href="'.e($TG).'" target="_blank" rel="noopener"><span class="elementor-screen-only">Telegram</span>'.$SVG_TG.'</a></span></div>';
    return widget($id, $cls, 'social-icons', $in);
}
function logo_img($mode) {
    global $LOGO, $LOGO_SRCSET;
    if ($mode === 'desk') return '<a href="../index.php"><img decoding="async" src="'.$LOGO.'" title="Logo" alt="Logo" loading="lazy"></a>';
    return '<a href="../index.php"><img fetchpriority="high" decoding="async" width="610" height="372" src="'.$LOGO.'" class="attachment-large size-large wp-image-1814" alt="" srcset="'.$LOGO_SRCSET.'" sizes="(max-width: 610px) 100vw, 610px"></a>';
}
?>
<!DOCTYPE html>
<html lang="en-US"><head>
<meta charset="UTF-8">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title><?= e($model['name']) ?> - Escort Website</title>
<meta property="og:type" content="article">
<meta property="og:title" content="<?= e($model['name']) ?> - Escort Website">
<meta property="og:image" content="<?= e(get_image_url($model['main_image'] ?? '', true)) ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="<?= e($LOGO) ?>" sizes="32x32">
<?php foreach (['main','all.min','simple-line-icons.min','style.min','a11y.min','frontend.min','post-7','widget-image.min','widget-social-icons.min','apple-webkit.min','widget-spacer.min','swiper.min','e-swiper.min','widget-image-carousel.min','widget-heading.min','widget-menu-anchor.min','post-1205','widgets','roboto','robotoslab','montserrat','aboreto','alike'] as $css): ?>
<link rel="stylesheet" href="./styles/<?= $css ?>.css" media="all">
<?php endforeach; ?>
<script src="./scripts/jquery.min.js"></script>
<script src="./scripts/jquery-migrate.min.js"></script>
<style>
.e-con.e-parent:nth-of-type(n+4):not(.e-lazyloaded):not(.e-no-lazyload),.e-con.e-parent:nth-of-type(n+4):not(.e-lazyloaded):not(.e-no-lazyload) *{background-image:none!important}
@media screen and (max-height:1024px){.e-con.e-parent:nth-of-type(n+3):not(.e-lazyloaded):not(.e-no-lazyload),.e-con.e-parent:nth-of-type(n+3):not(.e-lazyloaded):not(.e-no-lazyload) *{background-image:none!important}}
@media screen and (max-height:640px){.e-con.e-parent:nth-of-type(n+2):not(.e-lazyloaded):not(.e-no-lazyload),.e-con.e-parent:nth-of-type(n+2):not(.e-lazyloaded):not(.e-no-lazyload) *{background-image:none!important}}
.envato-block__preview{overflow:visible}.elementor-headline-animation-type-drop-in .elementor-headline-dynamic-wrapper{text-align:center}.envato-kit-141-display-inline{display:inline-block}
</style>
<style id="responsive-profile-css">
html,body{overflow-x:hidden}
*,*::before,*::after{box-sizing:border-box}
img{max-width:100%;height:auto}
.elementor-heading-title,.elementor-widget-text-editor,.elementor-widget-container p,.elementor-button-text{overflow-wrap:anywhere;word-break:normal}
.elementor-image-carousel-wrapper{width:100%;max-width:100%;overflow:hidden}
.elementor-image-carousel .swiper-slide{min-width:0}
.elementor-image-carousel .swiper-slide-inner{display:flex;align-items:center;justify-content:center;width:100%;height:100%;margin:0}
.elementor-image-carousel .swiper-slide-image{display:block;width:100%;height:auto;max-height:75vh;object-fit:contain}
@media (max-width:767px){
  /* Keep the mobile header completely independent from Elementor's
     container sizing/flex rules. */
  .mobile-site-header{
    width:100%;
    height:72px;
    min-height:72px;
    margin:0;
    padding:7px 10px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:8px;
    background:#000;
    border-bottom:1px solid #292929;
    box-sizing:border-box;
    overflow:hidden;
    position:relative;
    z-index:20;
  }

  .mobile-site-header__logo{
    flex:1 1 auto;
    min-width:0;
    height:56px;
    display:flex;
    align-items:center;
    justify-content:flex-start;
    overflow:hidden;
  }

  .mobile-site-header__logo a{
    display:flex;
    align-items:center;
    width:auto;
    height:100%;
    max-width:100%;
    text-decoration:none;
  }

  .mobile-site-header__logo img{
    display:block;
    width:auto!important;
    height:auto!important;
    max-width:155px!important;
    max-height:52px!important;
    object-fit:contain;
  }

  .mobile-site-header__social{
    flex:0 0 auto;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:5px;
  }

  .mobile-site-header__social a{
    width:28px;
    height:28px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    text-decoration:none;
    flex:none;
  }

  .mobile-site-header__social svg{
    display:block;
    width:22px;
    height:22px;
    fill:#fff;
  }

  .mobile-site-header__pickup{
    flex:0 0 auto;
    display:flex;
    align-items:center;
  }

  .mobile-site-header__pickup a{
    display:flex;
    align-items:center;
    justify-content:center;
    width:82px;
    height:36px;
    padding:0 8px;
    box-sizing:border-box;
    border:2px solid #d8ab2c;
    border-radius:3px;
    background:transparent;
    color:#d8ab2c;
    font-family:"Montserrat",sans-serif;
    font-size:11px;
    font-weight:600;
    line-height:1;
    text-decoration:none;
    white-space:nowrap;
  }

  .mobile-site-header__pickup a:hover,
  .mobile-site-header__pickup a:focus{
    background:transparent;
    color:#d8ab2c;
    border-color:#d8ab2c;
  }

  /* Prevent the old mobile Elementor header containers from affecting
     layout if they are cached/left in the DOM. */
  .elementor-element-76ae831,
  .elementor-element-ba4cfd0,
  .elementor-element-b1c2ecb,
  .elementor-element-b13704c,
  .elementor-element-fbae335{
    display:none!important;
  }

  .elementor-element-2a64c3c{
    margin-top:0!important;
  }

  .elementor-heading-title{
    white-space:normal;
    overflow-wrap:anywhere;
    line-height:1.3;
  }

  .elementor-element-c56af38 .elementor-image-carousel-wrapper{
    height:auto!important;
    min-height:0;
  }

  .elementor-element-c56af38 .elementor-image-carousel .swiper-slide{
    height:auto!important;
  }

  .elementor-element-c56af38 .elementor-image-carousel .swiper-slide-inner{
    height:auto!important;
  }

  .elementor-element-c56af38 .elementor-image-carousel .swiper-slide-image{
    display:block;
    width:100%!important;
    height:auto!important;
    max-height:none;
    object-fit:contain;
  }

  .elementor-widget-button .elementor-button{
    max-width:100%;
    white-space:normal;
    text-align:center;
  }
}

@media (max-width:380px){
  .mobile-site-header{
    height:68px;
    min-height:68px;
    padding:6px 8px;
    gap:5px;
  }

  .mobile-site-header__logo{
    height:54px;
  }

  .mobile-site-header__logo img{
    max-width:135px!important;
    max-height:48px!important;
  }

  .mobile-site-header__social{
    gap:2px;
  }

  .mobile-site-header__social a{
    width:25px;
    height:25px;
  }

  .mobile-site-header__social svg{
    width:20px;
    height:20px;
  }

  .mobile-site-header__pickup a{
    width:76px;
    height:34px;
    font-size:10px;
  }
}
</style>
<style>
:root{--owp-primary-color:#007a99;--owp-primary-color-hover:#005f78;--owp-link-color:#333333;--owp-link-color-hover:#007a99;--owp-button-bg-color:#007a99;--owp-button-bg-color-hover:#005f78;--owp-button-text-color:#ffffff;--owp-button-text-color-hover:#ffffff;--owp-button-padding-top:14px;--owp-button-padding-right:20px;--owp-button-padding-bottom:14px;--owp-button-padding-left:20px;--owp-button-font-size:14px;--owp-button-font-weight:600;--owp-button-letter-spacing:.05em;--owp-button-line-height:1.2;--owp-button-text-transform:none;--owp-input-text-color:#333333;--owp-input-border-color:#767676;--owp-input-border-color-focus:#333333;--owp-input-font-size:16px;--owp-input-line-height:1.6;--owp-widget-link-color:#007a99;--owp-widget-link-color-hover:#005f78;--owp-widget-link-text-decoration:underline;--owp-widget-link-text-decoration-hover:underline;--owp-widget-link-underline-offset:.2em;--owp-focus-outline-width:2px;--owp-focus-outline-offset:2px;--owp-focus-outline-color:currentColor;--owp-button-focus-outline-color:var(--owp-button-bg-color-hover,var(--owp-button-bg-color,var(--owp-primary-color,currentColor)))}
body .theme-button,body input[type="submit"],body button[type="submit"],body button,body .button,.wp-block-button__link{border-color:#ffffff}
body .theme-button:hover,body input[type="submit"]:hover,body button[type="submit"]:hover,body button:hover,body .button:hover,.wp-block-button__link:hover{border-color:#ffffff}
.theme-button,input[type="submit"],button[type="submit"],button,.button{border-style:solid;border-width:1px}
body{font-size:14px;line-height:1.8}
h1,h2,h3,h4,h5,h6{line-height:1.4}h1{font-size:23px}h2{font-size:20px}h3{font-size:18px}h4{font-size:17px}h5{font-size:14px}h6{font-size:15px}
</style>
<script src="./scripts/background-slideshow.min.js" async></script><script src="./scripts/background-video.min.js" async></script><script src="./scripts/image-carousel.min.js" async></script>
<style id="custom-escort-cards-css">
html,body{overflow-x:hidden;width:100%}
*,*::before,*::after{box-sizing:border-box}
img{max-width:100%;height:auto}
.elementor{overflow-x:hidden}
@media (max-width:767px){
.elementor{width:100%!important;max-width:100%!important}
.e-con-boxed{width:100%!important;max-width:100%!important;padding-left:0!important;padding-right:0!important}
.e-con-boxed>.e-con-inner{width:100%!important;max-width:100%!important;padding-left:10px!important;padding-right:10px!important}
.e-con,.e-flex,.e-con-full{width:100%!important;max-width:100%!important}
.elementor-widget-container{max-width:100%!important}
.e-transform{transform:none!important}
.elementor-image img,.elementor-widget-image img{width:100%!important;max-width:100%!important;height:auto!important}
}
.escort-cards-container{width:100%;max-width:1200px;margin:15px auto 40px;padding:0 15px}
.escort-cards-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:30px;width:100%}
.escort-item-card{position:relative;display:block;width:100%;height:480px;border-radius:40px;overflow:hidden;text-decoration:none;background-color:#121212;box-shadow:0 8px 25px rgba(0,0,0,.45);transition:transform .35s ease,box-shadow .35s ease;cursor:pointer;-webkit-tap-highlight-color:transparent}
.escort-item-card:hover{transform:translateY(-6px);box-shadow:0 16px 36px rgba(0,0,0,.7)}
.escort-item-card .escort-img-box{width:100%;height:100%;overflow:hidden;border-radius:40px}
.escort-item-card img{width:100%;height:100%;object-fit:cover;object-position:center top;display:block;border-radius:40px;transition:filter .6s ease,transform .6s ease}
.escort-item-card:hover img{filter:brightness(35%);transform:scale(1.03)}
.escort-card-name{position:absolute;top:26px;left:28px;font-family:"Montserrat",sans-serif;font-size:22px;font-weight:500;text-transform:uppercase;line-height:1.2;letter-spacing:1.4px;color:#fff;text-shadow:0 2px 8px rgba(0,0,0,.8);z-index:2;margin:0;padding:0;pointer-events:none}
.escort-card-button{position:absolute;bottom:24px;right:28px;display:flex;align-items:center;gap:10px;color:#fff;font-family:"Montserrat",sans-serif;font-size:18px;font-weight:400;text-transform:capitalize;text-shadow:0 2px 8px rgba(0,0,0,.8);z-index:2;pointer-events:none}
.escort-card-button svg{width:20px;height:20px;fill:#fff;transition:transform .3s ease}
.escort-item-card:hover .escort-card-button svg{transform:translateX(5px)}

html, body { background-color: #000; }
.escort-cards-container { background-color: #000; }
@media (hover:none){.escort-item-card:hover{transform:none}.escort-item-card:hover img{filter:none;transform:none}}
@media (max-width:991px){.escort-cards-container{padding:0 12px}.escort-cards-grid{grid-template-columns:repeat(2,1fr);gap:20px}.escort-item-card{height:400px;border-radius:30px}.escort-item-card .escort-img-box,.escort-item-card img{border-radius:30px}.escort-card-name{font-size:18px;top:20px;left:20px}.escort-card-button{font-size:15px;bottom:18px;right:18px}}
@media (max-width:575px){.escort-cards-container{padding:0 10px}.escort-cards-grid{grid-template-columns:1fr;gap:18px}.escort-item-card{height:360px;border-radius:24px}.escort-item-card .escort-img-box,.escort-item-card img{border-radius:24px}.escort-card-name{font-size:16px;letter-spacing:1px;top:18px;left:18px}.escort-card-button{font-size:13px;bottom:16px;right:16px;gap:7px}.escort-card-button svg{width:16px;height:16px}}
@media (max-width:400px){.escort-item-card{height:320px;border-radius:20px}.escort-item-card .escort-img-box,.escort-item-card img{border-radius:20px}.escort-card-name{font-size:14px;top:14px;left:14px}.escort-card-button{font-size:12px;bottom:12px;right:14px}}

/* The standalone header is phone-only */
@media (min-width:768px){
  .mobile-site-header{display:none!important}
}
</style>
</head>
<body class="wp-singular page-template page-template-elementor_canvas page page-id-1205 wp-embed-responsive wp-theme-oceanwp oceanwp-theme dropdown-mobile default-breakpoint has-sidebar content-right-sidebar has-topbar has-breadcrumbs elementor-default elementor-template-canvas elementor-kit-7 elementor-page elementor-page-1205 e--ua-blink e--ua-chrome e--ua-webkit" data-elementor-device-mode="tablet">
<div data-elementor-type="wp-page" data-elementor-id="1205" class="elementor elementor-1205">
<?php
// ---------- DESKTOP HEADER ----------
echo parent_con('1941fcc', 'elementor-hidden-mobile');
echo child_con('6965c8f'), w_image('d9e2971', logo_img('desk')), '</div>';
echo child_con('a5e24a6'), w_social('ffcd341', 'elementor-shape-rounded elementor-grid-0 e-grid-align-center', '623db3a', '3167f5e'), '</div>';
echo child_con('90b8599'), w_btn('f6db973', '#contact', 'PICK UP', '', 'e-transform elementor-align-center', $TX), '</div>';
echo child_con('5618bab'), w_btn('d2136d2', $WA, 'BECOME A MODEL', '', 'e-transform', $TX, true), '</div>';
echo '</div></div>';

// ---------- MOBILE HEADER ----------
/*
 * Standalone mobile header.
 * This intentionally does NOT use Elementor containers so the existing
 * Elementor flex/spacing rules cannot make the header vertically expand.
 */
echo '<header class="mobile-site-header" aria-label="Mobile site header">';

echo '<div class="mobile-site-header__logo">';
echo logo_img('mob');
echo '</div>';

echo '<div class="mobile-site-header__social" aria-label="Contact links">';
echo '<a href="' . e($WA) . '" target="_blank" rel="noopener" aria-label="WhatsApp">';
echo $SVG_WA;
echo '</a>';
echo '<a href="' . e($TG) . '" target="_blank" rel="noopener" aria-label="Telegram">';
echo $SVG_TG;
echo '</a>';
echo '</div>';

echo '<div class="mobile-site-header__pickup">';
echo '<a href="#contact" aria-label="Pick up">PICK UP</a>';
echo '</div>';

echo '</header>';

// ---------- CAROUSEL + DETAILS ----------
echo parent_con('2a64c3c', '');
echo child_con('4d78d64');
$slides = '';
foreach ($gallery as $i => $g) {
    $slides .= '<div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="'.($i+1).' / '.count($gallery).'"><figure class="swiper-slide-inner"><img decoding="async" class="swiper-slide-image" src="'.e(get_image_url($g, true)).'" alt="'.e($model['name']).'"></figure></div>';
}
$carousel = '<div class="elementor-image-carousel-wrapper swiper" role="region" aria-roledescription="carousel" aria-label="Image Carousel" dir="ltr"><div class="elementor-image-carousel swiper-wrapper swiper-image-stretch">'.$slides.'</div>'
    .'<div class="elementor-swiper-button elementor-swiper-button-prev" role="button" tabindex="0" aria-label="Previous slide">'.$SVG_CL.'</div>'
    .'<div class="elementor-swiper-button elementor-swiper-button-next" role="button" tabindex="0" aria-label="Next slide">'.$SVG_CR.'</div>'
    .'<span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>';
$cw = widget('c56af38', 'elementor-arrows-position-inside', 'image-carousel', $carousel,
    '{&quot;slides_to_show&quot;:&quot;2&quot;,&quot;slides_to_scroll&quot;:&quot;2&quot;,&quot;navigation&quot;:&quot;arrows&quot;,&quot;slides_to_show_mobile&quot;:&quot;1&quot;,&quot;slides_to_scroll_mobile&quot;:&quot;1&quot;,&quot;autoplay&quot;:&quot;yes&quot;,&quot;pause_on_hover&quot;:&quot;yes&quot;,&quot;pause_on_interaction&quot;:&quot;yes&quot;,&quot;autoplay_speed&quot;:5000,&quot;infinite&quot;:&quot;yes&quot;,&quot;speed&quot;:500}');
echo str_replace('elementor-widget-image-carousel"', 'elementor-widget-image-carousel e-widget-swiper"', $cw);
echo '</div>';

echo child_con('08ad2ad');
echo w_heading('a878f63', mb_strtoupper(e($model['name']), 'UTF-8'));
echo w_heading('154e590', 'Age: ' . e($model['age']));
echo w_heading('443e587', 'Height: ' . e($model['height']));
echo w_heading('d050b08', 'Weight: ' . e($model['weight']));
echo w_heading('4dd7a80', ($isBoy ? 'Chest' : 'Breast') . ' size: ' . e($model['breast_size']));
echo w_heading('1100fce', 'Hair: ' . e($model['hair']));
$origin = '';
foreach (['origin', 'origen', 'country', 'nationality'] as $k) { if (!empty($model[$k])) { $origin = $model[$k]; break; } }
if ($origin !== '') echo w_heading('1100fce', 'Origen: ' . e($origin));
echo w_heading('35b6a2f', !empty($model['bio']) ? nl2br(e($model['bio'])) : 'Would you like to spend time <br>with an elite model?');
echo w_btn('a2688ed', $WA, 'CONTACT THE MANAGER', $SVG_ARR, '', '', true);
echo '</div></div></div>';

echo parent_con('bfdf5de', ''), w_spacer('b74a4e0'), '</div></div>';
echo parent_con('1c406f6', ''), w_heading('b4378d9', 'More ' . ($isBoy ? 'Boys' : 'Girls')), '</div></div>';
echo parent_con('055f661', ''), w_spacer('0d28b9a'), '</div></div>';
?>
<div class="escort-cards-container"><div class="escort-cards-grid">
<?php foreach ($moreModels as $o): ?>
<a href="?id=<?= (int)$o['id'] ?>" class="escort-item-card">
  <div class="escort-img-box"><img decoding="async" src="<?= e(get_image_url($o['main_image'], true)) ?>" alt="<?= e($o['name']) ?>" loading="lazy"></div>
  <div class="escort-card-name"><?= e($o['name']) ?></div>
  <div class="escort-card-button"><span>View Profile</span><?= str_replace('<path ', '<path fill="#fff" ', $SVG_ARR) ?></div>
</a>
<?php endforeach; ?>
</div></div>
<?php
// ---------- CONTACT (desktop) ----------
echo parent_con('e0048ee', 'elementor-hidden-mobile'), w_anchor('c6d0463', 'contact'), '</div></div>';
echo parent_con('4189606', 'elementor-hidden-mobile'), child_con('7734f5a', true), w_heading('0af01c5', 'how to contact us'), '</div></div></div>';
echo parent_con('cca1aa9', 'elementor-hidden-mobile');
echo child_con('0c6cee9', true), w_image('8d3fef9', logo_img('mob')), '</div>';
echo child_con('0ad0571', true), w_heading('332cd6e', '&copy; ' . date('Y') . ' Euroescortbangkok'), '</div>';
echo child_con('5e9e166', true), w_btn('49bf90c', $WA, 'WHATSAPP', $SVG_WA, '', '', true), '</div>';
echo child_con('0dad96c', true), w_btn('8a61c78', $TG, 'TELEGRAM', $SVG_TGP, '', '', true), '</div>';
echo '</div></div>';

// ---------- CONTACT (mobile) ----------
echo parent_con('122da0c', 'elementor-hidden-desktop'), w_anchor('df2bd4c', 'girls'), '</div></div>';
echo parent_con('5ffa510', 'elementor-hidden-desktop'), child_con('0f68327', true), w_heading('95eb65f', 'How To Contact Us'), '</div></div></div>';
echo parent_con('8fa68c5', 'elementor-hidden-desktop');
echo child_con('e4a5aee', true), w_image('7cfa498', logo_img('mob')), '</div>';
echo child_con('54f4ec7', true), w_heading('cceb5da', '&copy; ' . date('Y') . ' Euroescortbangkok'), '</div>';
echo child_con('d4054e3', true), w_btn('610b643', $WA, 'WHATSAPP', $SVG_WA, 'elementor-mobile-align-center', '', true), '</div>';
echo child_con('a81e51f', true), w_btn('6e412a5', $TG, 'TELEGRAM', $SVG_TGP, 'elementor-mobile-align-center', '', true), w_spacer('7c9823b'), '</div>';
echo '</div></div>';
?>
</div>

<!-- Click to Chat (original plugin markup) -->
<style id="ht-ctc-entry-animations">.ht_ctc_entry_animation{animation-duration:.4s;animation-fill-mode:both;animation-delay:0s;animation-iteration-count:1}@keyframes ht_ctc_anim_corner{0%{opacity:0;transform:scale(0)}100%{opacity:1;transform:scale(1)}}.ht_ctc_an_entry_corner{animation-name:ht_ctc_anim_corner;animation-timing-function:cubic-bezier(.25,1,.5,1);transform-origin:bottom var(--side,right)}</style>
<div class="ht-ctc ht-ctc-chat ctc-analytics ctc_wp_desktop style-2 ht_ctc_entry_animation ht_ctc_an_entry_corner ht_ctc_animation no-animation" id="ht-ctc-chat" style="position:fixed;bottom:15px;right:15px;cursor:pointer;z-index:99999999;--side:right;">
<a href="<?= e($WA) ?>" target="_blank" rel="noopener" aria-label="WhatsApp" style="display:block">
<svg style="pointer-events:none;display:block;height:50px;width:50px;" width="50px" height="50px" viewBox="0 0 1024 1024">
<defs><path id="htwasqicona-chat" d="M1023.941 765.153c0 5.606-.171 17.766-.508 27.159-.824 22.982-2.646 52.639-5.401 66.151-4.141 20.306-10.392 39.472-18.542 55.425-9.643 18.871-21.943 35.775-36.559 50.364-14.584 14.56-31.472 26.812-50.315 36.416-16.036 8.172-35.322 14.426-55.744 18.549-13.378 2.701-42.812 4.488-65.648 5.3-9.402.336-21.564.505-27.15.505l-504.226-.081c-5.607 0-17.765-.172-27.158-.509-22.983-.824-52.639-2.646-66.152-5.4-20.306-4.142-39.473-10.392-55.425-18.542-18.872-9.644-35.775-21.944-50.364-36.56-14.56-14.584-26.812-31.471-36.415-50.314-8.174-16.037-14.428-35.323-18.551-55.744-2.7-13.378-4.487-42.812-5.3-65.649-.334-9.401-.503-21.563-.503-27.148l.08-504.228c0-5.607.171-17.766.508-27.159.825-22.983 2.646-52.639 5.401-66.151 4.141-20.306 10.391-39.473 18.542-55.426C34.154 93.24 46.455 76.336 61.07 61.747c14.584-14.559 31.472-26.812 50.315-36.416 16.037-8.172 35.324-14.426 55.745-18.549 13.377-2.701 42.812-4.488 65.648-5.3 9.402-.335 21.565-.504 27.149-.504l504.227.081c5.608 0 17.766.171 27.159.508 22.983.825 52.638 2.646 66.152 5.401 20.305 4.141 39.472 10.391 55.425 18.542 18.871 9.643 35.774 21.944 50.363 36.559 14.559 14.584 26.812 31.471 36.415 50.315 8.174 16.037 14.428 35.323 18.551 55.744 2.7 13.378 4.486 42.812 5.3 65.649.335 9.402.504 21.564.504 27.15l-.082 504.226z"/></defs>
<linearGradient id="htwasqiconb-chat" gradientUnits="userSpaceOnUse" x1="512.001" y1=".978" x2="512.001" y2="1025.023"><stop offset="0" stop-color="#61fd7d"/><stop offset="1" stop-color="#2bb826"/></linearGradient>
<use xlink:href="#htwasqicona-chat" overflow="visible" style="fill:url(#htwasqiconb-chat)" fill="url(#htwasqiconb-chat)"/>
<g><path style="fill:#FFFFFF;" fill="#FFF" d="M783.302 243.246c-69.329-69.387-161.529-107.619-259.763-107.658-202.402 0-367.133 164.668-367.214 367.072-.026 64.699 16.883 127.854 49.017 183.522l-52.096 190.229 194.665-51.047c53.636 29.244 114.022 44.656 175.482 44.682h.151c202.382 0 367.128-164.688 367.21-367.094.039-98.087-38.121-190.319-107.452-259.706zM523.544 808.047h-.125c-54.767-.021-108.483-14.729-155.344-42.529l-11.146-6.612-115.517 30.293 30.834-112.592-7.259-11.544c-30.552-48.579-46.688-104.729-46.664-162.379.066-168.229 136.985-305.096 305.339-305.096 81.521.031 158.154 31.811 215.779 89.482s89.342 134.332 89.312 215.859c-.066 168.243-136.984 305.118-305.209 305.118zm167.415-228.515c-9.177-4.591-54.286-26.782-62.697-29.843-8.41-3.062-14.526-4.592-20.645 4.592-6.115 9.182-23.699 29.843-29.053 35.964-5.352 6.122-10.704 6.888-19.879 2.296-9.176-4.591-38.74-14.277-73.786-45.526-27.275-24.319-45.691-54.359-51.043-63.543-5.352-9.183-.569-14.146 4.024-18.72 4.127-4.109 9.175-10.713 13.763-16.069 4.587-5.355 6.117-9.183 9.175-15.304 3.059-6.122 1.529-11.479-.765-16.07-2.293-4.591-20.644-49.739-28.29-68.104-7.447-17.886-15.013-15.466-20.645-15.747-5.346-.266-11.469-.322-17.585-.322s-16.057 2.295-24.467 11.478-32.113 31.374-32.113 76.521c0 45.147 32.877 88.764 37.465 94.885 4.588 6.122 64.699 98.771 156.741 138.502 21.892 9.45 38.982 15.094 52.308 19.322 21.98 6.979 41.982 5.995 57.793 3.634 17.628-2.633 54.284-22.189 61.932-43.615 7.646-21.427 7.646-39.791 5.352-43.617-2.294-3.826-8.41-6.122-17.585-10.714z"/></g></svg></a></div>

<script>
(()=>{const run=()=>{const els=document.querySelectorAll('.e-con.e-parent:not(.e-lazyloaded)');const ob=new IntersectionObserver(en=>{en.forEach(x=>{if(x.isIntersecting){x.target.classList.add('e-lazyloaded');ob.unobserve(x.target)}})},{rootMargin:'200px 0px 200px 0px'});els.forEach(x=>ob.observe(x))};['DOMContentLoaded','elementor/lazyload/observe'].forEach(ev=>document.addEventListener(ev,run))})();
</script>
<script>
var ht_ctc_chat_var={"number":"<?= e(preg_replace('/\D+/', '', $waRaw) ?: '61489987819') ?>","pre_filled":"How May I Help you...?","dis_m":"show","dis_d":"show","css":"cursor: pointer; z-index: 99999999;","pos_d":"position: fixed; bottom: 15px; right: 15px;","pos_m":"position: fixed; bottom: 15px; right: 15px;","side_d":"right","side_m":"right","schedule":"no","se":"150","ani":"no-animation","page_id":"1205","url_target_d":"_blank"};
var oceanwpLocalize={"isRTL":"","menuSearchStyle":"drop_down","mobileMenuSearchStyle":"disabled","sidrSource":null,"sidrDisplace":"1","sidrSide":"left","sidrDropdownTarget":"link","verticalHeaderTarget":"link","mobileDropdownTarget":"link","semanticMobileHeader":"1","semanticDesktopHeader":"1","customScrollOffset":"0","customSelects":".woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select","loadMoreLoadingText":"Loading...","ajax_url":""};
var elementorFrontendConfig={"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"previous":"Previous","next":"Next","close":"Close","a11yCarouselPrevSlideMessage":"Previous slide","a11yCarouselNextSlideMessage":"Next slide","a11yCarouselFirstSlideMessage":"This is the first slide","a11yCarouselLastSlideMessage":"This is the last slide","a11yCarouselPaginationBulletMessage":"Go to slide"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"Mobile Portrait","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Mobile Landscape","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"Tablet Portrait","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet Landscape","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"Laptop","value":1366,"default_value":1366,"direction":"max","is_enabled":false},"widescreen":{"label":"Widescreen","value":2400,"default_value":2400,"direction":"min","is_enabled":false}},"hasCustomBreakpoints":false},"version":"4.3.2","is_static":false,"experimentalFeatures":{"e_font_icon_svg":true,"additional_custom_breakpoints":true,"container":true,"nested-elements":true},"urls":{"assets":"","ajaxurl":"","uploadUrl":""},"nonces":{},"swiperClass":"swiper","settings":{"page":[],"editorPreferences":[]},"kit":{"active_breakpoints":["viewport_mobile","viewport_tablet"]},"post":{"id":1205,"title":"","excerpt":"","featuredImage":false}};
</script>
<script data-wp-strategy="defer" defer src="./scripts/app.js"></script>
<?php foreach (['imagesloaded.min','theme.min','drop-down-mobile-menu.min','drop-down-search.min','magnific-popup.min','ow-lightbox.min','flickity.pkgd.min','ow-slider.min','scroll-effect.min','scroll-top.min','select.min','webpack.runtime.min','frontend-modules.min','core.min','frontend.min','swiper.min'] as $js): ?>
<script src="./scripts/<?= $js ?>.js"></script>
<?php endforeach; ?>
<span id="elementor-device-mode" class="elementor-screen-only"></span>
</body></html>