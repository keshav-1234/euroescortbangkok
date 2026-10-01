<?php
require_once __DIR__ . '/../includes/db.php';
$db = getDB();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    $model = $db->query("SELECT * FROM models WHERE status = 'active' ORDER BY id ASC LIMIT 1")->fetch();
} else {
    $stmt = $db->prepare("SELECT * FROM models WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $model = $stmt->fetch();
}

if (!$model) {
    header("Location: ../index.php");
    exit;
}

// Track profile view count once per session
if (empty($_SESSION['viewed_model_' . $model['id']])) {
    $_SESSION['viewed_model_' . $model['id']] = true;
    $db->prepare("UPDATE models SET views_count = views_count + 1 WHERE id = :id")->execute([':id' => $model['id']]);
}

// Gallery images
$gallery = !empty($model['gallery_images']) ? json_decode($model['gallery_images'], true) : [];
if (!is_array($gallery) || empty($gallery)) {
    $gallery = [$model['main_image']];
}
while (count($gallery) < 4) {
    $gallery = array_merge($gallery, $gallery);
}

// More models
$stmtMore = $db->prepare("SELECT * FROM models WHERE id != :id AND status = 'active' ORDER BY (gender = :gender) DESC, sort_order ASC, RAND() LIMIT 12");
$stmtMore->execute([':id' => $model['id'], ':gender' => $model['gender']]);
$moreModels = $stmtMore->fetchAll();
?>
<html lang="en-US"><head>
	<meta charset="UTF-8">
		<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- This site is optimized with the Yoast SEO plugin v28.5 - https://yoast.com/product/yoast-seo-wordpress/ -->
	<title><?= e($model['name']) ?> - Escort Website</title>
	<link rel="canonical" href="https://euroescortbangkok.com/tonya/">
	<meta property="og:locale" content="en_US">
	<meta property="og:type" content="article">
	<meta property="og:title" content="Tonya - Escort Website">
	<meta property="og:description" content="Whatsapp Telegram PICK UP BECOME A MODEL Whatsapp Telegram PICK UP TONYA Age: 25 Height: 168 cm Weight: 55 kg Breast size: 36B Hair: Black Would you like to spend time with an elite model? CONTACT THE MANAGER More Girls view Profile Eva view Profile Milana view Profile Bale view Profile Faiza view Profile Rebeka […]">
	<meta property="og:url" content="https://euroescortbangkok.com/tonya/">
	<meta property="og:site_name" content="Escort Website">
	<meta property="article:modified_time" content="2026-09-26T07:31:44+00:00">
	<meta property="og:image" content="https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM.png">
	<meta property="og:image:width" content="610">
	<meta property="og:image:height" content="372">
	<meta property="og:image:type" content="image/png">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:label1" content="Est. reading time">
	<meta name="twitter:data1" content="8 minutes">
	<script type="application/ld+json" class="yoast-schema-graph">{"@context":"https:\/\/schema.org","@graph":[{"@type":"WebPage","@id":"https:\/\/euroescortbangkok.com\/tonya\/","url":"https:\/\/euroescortbangkok.com\/tonya\/","name":"Tonya - Escort Website","isPartOf":{"@id":"https:\/\/euroescortbangkok.com\/#website"},"primaryImageOfPage":{"@id":"https:\/\/euroescortbangkok.com\/tonya\/#primaryimage"},"image":{"@id":"https:\/\/euroescortbangkok.com\/tonya\/#primaryimage"},"thumbnailUrl":"https:\/\/euroescortbangkok.com\/wp-content\/uploads\/2026\/05\/Screenshot-2026-05-04-at-4.02.44-PM.png","datePublished":"2025-04-14T14:38:08+00:00","dateModified":"2026-09-26T07:31:44+00:00","breadcrumb":{"@id":"https:\/\/euroescortbangkok.com\/tonya\/#breadcrumb"},"inLanguage":"en-US","potentialAction":[{"@type":"ReadAction","target":["https:\/\/euroescortbangkok.com\/tonya\/"]}]},{"@type":"ImageObject","inLanguage":"en-US","@id":"https:\/\/euroescortbangkok.com\/tonya\/#primaryimage","url":"https:\/\/euroescortbangkok.com\/wp-content\/uploads\/2026\/05\/Screenshot-2026-05-04-at-4.02.44-PM.png","contentUrl":"https:\/\/euroescortbangkok.com\/wp-content\/uploads\/2026\/05\/Screenshot-2026-05-04-at-4.02.44-PM.png","width":610,"height":372},{"@type":"BreadcrumbList","@id":"https:\/\/euroescortbangkok.com\/tonya\/#breadcrumb","itemListElement":[{"@type":"ListItem","position":1,"name":"Home","item":"https:\/\/euroescortbangkok.com\/"},{"@type":"ListItem","position":2,"name":"Tonya"}]},{"@type":"WebSite","@id":"https:\/\/euroescortbangkok.com\/#website","url":"https:\/\/euroescortbangkok.com\/","name":"Escort Website","description":"Best Escorts Website In Thailand","potentialAction":[{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"https:\/\/euroescortbangkok.com\/?s={search_term_string}"},"query-input":{"@type":"PropertyValueSpecification","valueRequired":true,"valueName":"search_term_string"}}],"inLanguage":"en-US"}]}</script>
	<!-- / Yoast SEO plugin. -->


<link rel="alternate" type="application/rss+xml" title="Escort Website » Feed" href="https://euroescortbangkok.com/feed/">
<link rel="alternate" type="application/rss+xml" title="Escort Website » Comments Feed" href="https://euroescortbangkok.com/comments/feed/">
<link rel="alternate" title="oEmbed (JSON)" type="application/json+oembed" href="https://euroescortbangkok.com/wp-json/oembed/1.0/embed?url=https%3A%2F%2Feuroescortbangkok.com%2Ftonya%2F">
<link rel="alternate" title="oEmbed (XML)" type="text/xml+oembed" href="https://euroescortbangkok.com/wp-json/oembed/1.0/embed?url=https%3A%2F%2Feuroescortbangkok.com%2Ftonya%2F&format=xml">
<style id="wp-img-auto-sizes-contain-inline-css">
img:is([sizes=auto i],[sizes^="auto," i]){contain-intrinsic-size:3000px 1500px}
/*# sourceURL=wp-img-auto-sizes-contain-inline-css */
</style>
<link rel="stylesheet" id="ht_ctc_main_css-css" href="./styles/main.css" media="all">
<style id="wp-emoji-styles-inline-css">

	img.wp-smiley, img.emoji {
		display: inline !important;
		border: none !important;
		box-shadow: none !important;
		height: 1em !important;
		width: 1em !important;
		margin: 0 0.07em !important;
		vertical-align: -0.1em !important;
		background: none !important;
		padding: 0 !important;
	}
/*# sourceURL=wp-emoji-styles-inline-css */
</style>
<style id="classic-theme-styles-inline-css">
/*! This file is auto-generated */
.wp-block-button__link{color:#fff;background-color:#32373c;border-radius:9999px;box-shadow:none;text-decoration:none;padding:calc(.667em + 2px) calc(1.333em + 2px);font-size:1.125em}.wp-block-file__button{background:#32373c;color:#fff;text-decoration:none}
/*# sourceURL=/wp-includes/css/classic-themes.min.css */
</style>
<style id="global-styles-inline-css">
:root{--wp--preset--aspect-ratio--square: 1;--wp--preset--aspect-ratio--4-3: 4/3;--wp--preset--aspect-ratio--3-4: 3/4;--wp--preset--aspect-ratio--3-2: 3/2;--wp--preset--aspect-ratio--2-3: 2/3;--wp--preset--aspect-ratio--16-9: 16/9;--wp--preset--aspect-ratio--9-16: 9/16;--wp--preset--color--black: #000000;--wp--preset--color--cyan-bluish-gray: #abb8c3;--wp--preset--color--white: #ffffff;--wp--preset--color--pale-pink: #f78da7;--wp--preset--color--vivid-red: #cf2e2e;--wp--preset--color--luminous-vivid-orange: #ff6900;--wp--preset--color--luminous-vivid-amber: #fcb900;--wp--preset--color--light-green-cyan: #7bdcb5;--wp--preset--color--vivid-green-cyan: #00d084;--wp--preset--color--pale-cyan-blue: #8ed1fc;--wp--preset--color--vivid-cyan-blue: #0693e3;--wp--preset--color--vivid-purple: #9b51e0;--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple: linear-gradient(135deg,rgb(6,147,227) 0%,rgb(155,81,224) 100%);--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan: linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%);--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange: linear-gradient(135deg,rgb(252,185,0) 0%,rgb(255,105,0) 100%);--wp--preset--gradient--luminous-vivid-orange-to-vivid-red: linear-gradient(135deg,rgb(255,105,0) 0%,rgb(207,46,46) 100%);--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray: linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%);--wp--preset--gradient--cool-to-warm-spectrum: linear-gradient(135deg,rgb(74,234,220) 0%,rgb(151,120,209) 20%,rgb(207,42,186) 40%,rgb(238,44,130) 60%,rgb(251,105,98) 80%,rgb(254,248,76) 100%);--wp--preset--gradient--blush-light-purple: linear-gradient(135deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%);--wp--preset--gradient--blush-bordeaux: linear-gradient(135deg,rgb(254,205,165) 0%,rgb(254,45,45) 50%,rgb(107,0,62) 100%);--wp--preset--gradient--luminous-dusk: linear-gradient(135deg,rgb(255,203,112) 0%,rgb(199,81,192) 50%,rgb(65,88,208) 100%);--wp--preset--gradient--pale-ocean: linear-gradient(135deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%);--wp--preset--gradient--electric-grass: linear-gradient(135deg,rgb(202,248,128) 0%,rgb(113,206,126) 100%);--wp--preset--gradient--midnight: linear-gradient(135deg,rgb(2,3,129) 0%,rgb(40,116,252) 100%);--wp--preset--font-size--small: 13px;--wp--preset--font-size--medium: 20px;--wp--preset--font-size--large: 36px;--wp--preset--font-size--x-large: 42px;--wp--preset--spacing--20: 0.44rem;--wp--preset--spacing--30: 0.67rem;--wp--preset--spacing--40: 1rem;--wp--preset--spacing--50: 1.5rem;--wp--preset--spacing--60: 2.25rem;--wp--preset--spacing--70: 3.38rem;--wp--preset--spacing--80: 5.06rem;--wp--preset--shadow--natural: 6px 6px 9px rgba(0, 0, 0, 0.2);--wp--preset--shadow--deep: 12px 12px 50px rgba(0, 0, 0, 0.4);--wp--preset--shadow--sharp: 6px 6px 0px rgba(0, 0, 0, 0.2);--wp--preset--shadow--outlined: 6px 6px 0px -3px rgb(255, 255, 255), 6px 6px rgb(0, 0, 0);--wp--preset--shadow--crisp: 6px 6px 0px rgb(0, 0, 0);}:where(body) { margin: 0; }:where(.is-layout-flex){gap: 0.5em;}:where(.is-layout-grid){gap: 0.5em;}body .is-layout-flex{display: flex;}.is-layout-flex{flex-wrap: wrap;align-items: center;}.is-layout-flex > :is(*, div){margin: 0;}body .is-layout-grid{display: grid;}.is-layout-grid > :is(*, div){margin: 0;}body{padding-top: 0px;padding-right: 0px;padding-bottom: 0px;padding-left: 0px;}:root :where(.wp-element-button, .wp-block-button__link){background-color: #32373c;border-width: 0;color: #fff;font-family: inherit;font-size: inherit;font-style: inherit;font-weight: inherit;letter-spacing: inherit;line-height: inherit;padding-top: calc(0.667em + 2px);padding-right: calc(1.333em + 2px);padding-bottom: calc(0.667em + 2px);padding-left: calc(1.333em + 2px);text-decoration: none;text-transform: inherit;}.has-black-color{color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-color{color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-color{color: var(--wp--preset--color--white) !important;}.has-pale-pink-color{color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-color{color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-color{color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-color{color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-color{color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-color{color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-color{color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-color{color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-color{color: var(--wp--preset--color--vivid-purple) !important;}.has-black-background-color{background-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-background-color{background-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-background-color{background-color: var(--wp--preset--color--white) !important;}.has-pale-pink-background-color{background-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-background-color{background-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-background-color{background-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-background-color{background-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-background-color{background-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-background-color{background-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-background-color{background-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-background-color{background-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-background-color{background-color: var(--wp--preset--color--vivid-purple) !important;}.has-black-border-color{border-color: var(--wp--preset--color--black) !important;}.has-cyan-bluish-gray-border-color{border-color: var(--wp--preset--color--cyan-bluish-gray) !important;}.has-white-border-color{border-color: var(--wp--preset--color--white) !important;}.has-pale-pink-border-color{border-color: var(--wp--preset--color--pale-pink) !important;}.has-vivid-red-border-color{border-color: var(--wp--preset--color--vivid-red) !important;}.has-luminous-vivid-orange-border-color{border-color: var(--wp--preset--color--luminous-vivid-orange) !important;}.has-luminous-vivid-amber-border-color{border-color: var(--wp--preset--color--luminous-vivid-amber) !important;}.has-light-green-cyan-border-color{border-color: var(--wp--preset--color--light-green-cyan) !important;}.has-vivid-green-cyan-border-color{border-color: var(--wp--preset--color--vivid-green-cyan) !important;}.has-pale-cyan-blue-border-color{border-color: var(--wp--preset--color--pale-cyan-blue) !important;}.has-vivid-cyan-blue-border-color{border-color: var(--wp--preset--color--vivid-cyan-blue) !important;}.has-vivid-purple-border-color{border-color: var(--wp--preset--color--vivid-purple) !important;}.has-vivid-cyan-blue-to-vivid-purple-gradient-background{background: var(--wp--preset--gradient--vivid-cyan-blue-to-vivid-purple) !important;}.has-light-green-cyan-to-vivid-green-cyan-gradient-background{background: var(--wp--preset--gradient--light-green-cyan-to-vivid-green-cyan) !important;}.has-luminous-vivid-amber-to-luminous-vivid-orange-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-amber-to-luminous-vivid-orange) !important;}.has-luminous-vivid-orange-to-vivid-red-gradient-background{background: var(--wp--preset--gradient--luminous-vivid-orange-to-vivid-red) !important;}.has-very-light-gray-to-cyan-bluish-gray-gradient-background{background: var(--wp--preset--gradient--very-light-gray-to-cyan-bluish-gray) !important;}.has-cool-to-warm-spectrum-gradient-background{background: var(--wp--preset--gradient--cool-to-warm-spectrum) !important;}.has-blush-light-purple-gradient-background{background: var(--wp--preset--gradient--blush-light-purple) !important;}.has-blush-bordeaux-gradient-background{background: var(--wp--preset--gradient--blush-bordeaux) !important;}.has-luminous-dusk-gradient-background{background: var(--wp--preset--gradient--luminous-dusk) !important;}.has-pale-ocean-gradient-background{background: var(--wp--preset--gradient--pale-ocean) !important;}.has-electric-grass-gradient-background{background: var(--wp--preset--gradient--electric-grass) !important;}.has-midnight-gradient-background{background: var(--wp--preset--gradient--midnight) !important;}.has-small-font-size{font-size: var(--wp--preset--font-size--small) !important;}.has-medium-font-size{font-size: var(--wp--preset--font-size--medium) !important;}.has-large-font-size{font-size: var(--wp--preset--font-size--large) !important;}.has-x-large-font-size{font-size: var(--wp--preset--font-size--x-large) !important;}
:root :where(.wp-block-icon svg){width: 24px;}
:where(.wp-block-post-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-post-template.is-layout-grid){gap: 1.25em;}
:where(.wp-block-term-template.is-layout-flex){gap: 1.25em;}:where(.wp-block-term-template.is-layout-grid){gap: 1.25em;}
:where(.wp-block-columns.is-layout-flex){gap: 2em;}:where(.wp-block-columns.is-layout-grid){gap: 2em;}
:root :where(.wp-block-pullquote){font-size: 1.5em;line-height: 1.6;}
/*# sourceURL=global-styles-inline-css */
</style>
<link rel="stylesheet" id="font-awesome-css" href="./styles/all.min.css" media="all">
<link rel="stylesheet" id="simple-line-icons-css" href="./styles/simple-line-icons.min.css" media="all">
<link rel="stylesheet" id="oceanwp-style-css" href="./styles/style.min.css" media="all">
<link rel="stylesheet" id="oceanwp-a11y-style-css" href="./styles/a11y.min.css" media="all">
<link rel="stylesheet" id="elementor-frontend-css" href="./styles/frontend.min.css" media="all">
<link rel="stylesheet" id="elementor-post-7-css" href="./styles/post-7.css" media="all">
<link rel="stylesheet" id="widget-image-css" href="./styles/widget-image.min.css" media="all">
<link rel="stylesheet" id="widget-social-icons-css" href="./styles/widget-social-icons.min.css" media="all">
<link rel="stylesheet" id="e-apple-webkit-css" href="./styles/apple-webkit.min.css" media="all">
<link rel="stylesheet" id="widget-spacer-css" href="./styles/widget-spacer.min.css" media="all">
<link rel="stylesheet" id="swiper-css" href="./styles/swiper.min.css" media="all">
<link rel="stylesheet" id="e-swiper-css" href="./styles/e-swiper.min.css" media="all">
<link rel="stylesheet" id="widget-image-carousel-css" href="./styles/widget-image-carousel.min.css" media="all">
<link rel="stylesheet" id="widget-heading-css" href="./styles/widget-heading.min.css" media="all">
<link rel="stylesheet" id="widget-menu-anchor-css" href="./styles/widget-menu-anchor.min.css" media="all">
<link rel="stylesheet" id="elementor-post-1205-css" href="./styles/post-1205.css" media="all">
<link rel="stylesheet" id="oe-widgets-style-css" href="./styles/widgets.css" media="all">
<link rel="stylesheet" id="elementor-gf-local-roboto-css" href="./styles/roboto.css" media="all">
<link rel="stylesheet" id="elementor-gf-local-robotoslab-css" href="./styles/robotoslab.css" media="all">
<link rel="stylesheet" id="elementor-gf-local-montserrat-css" href="./styles/montserrat.css" media="all">
<link rel="stylesheet" id="elementor-gf-local-aboreto-css" href="./styles/aboreto.css" media="all">
<link rel="stylesheet" id="elementor-gf-local-alike-css" href="./styles/alike.css" media="all">
<script id="jquery-core-js" src="./scripts/jquery.min.js"></script>
<script id="jquery-migrate-js" src="./scripts/jquery-migrate.min.js"></script>
<link rel="https://api.w.org/" href="https://euroescortbangkok.com/wp-json/"><link rel="alternate" title="JSON" type="application/json" href="https://euroescortbangkok.com/wp-json/wp/v2/pages/1205"><link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://euroescortbangkok.com/xmlrpc.php?rsd">
<meta name="generator" content="WordPress 7.0.6">
<link rel="shortlink" href="https://euroescortbangkok.com/?p=1205">
<meta name="generator" content="Elementor 4.3.2; features: e_font_icon_svg, additional_custom_breakpoints; settings: css_print_method-external, google_font-enabled, font_display-swap">
			<style>
				.e-con.e-parent:nth-of-type(n+4):not(.e-lazyloaded):not(.e-no-lazyload),
				.e-con.e-parent:nth-of-type(n+4):not(.e-lazyloaded):not(.e-no-lazyload) * {
					background-image: none !important;
				}
				@media screen and (max-height: 1024px) {
					.e-con.e-parent:nth-of-type(n+3):not(.e-lazyloaded):not(.e-no-lazyload),
					.e-con.e-parent:nth-of-type(n+3):not(.e-lazyloaded):not(.e-no-lazyload) * {
						background-image: none !important;
					}
				}
				@media screen and (max-height: 640px) {
					.e-con.e-parent:nth-of-type(n+2):not(.e-lazyloaded):not(.e-no-lazyload),
					.e-con.e-parent:nth-of-type(n+2):not(.e-lazyloaded):not(.e-no-lazyload) * {
						background-image: none !important;
					}
				}
			</style>
			<link rel="icon" href="https://euroescortbangkok.com/wp-content/uploads/2026/05/cropped-Screenshot-2026-05-04-at-4.02.44-PM-32x32.png" sizes="32x32">
<link rel="icon" href="https://euroescortbangkok.com/wp-content/uploads/2026/05/cropped-Screenshot-2026-05-04-at-4.02.44-PM-192x192.png" sizes="192x192">
<link rel="apple-touch-icon" href="https://euroescortbangkok.com/wp-content/uploads/2026/05/cropped-Screenshot-2026-05-04-at-4.02.44-PM-180x180.png">
<meta name="msapplication-TileImage" content="https://euroescortbangkok.com/wp-content/uploads/2026/05/cropped-Screenshot-2026-05-04-at-4.02.44-PM-270x270.png">
<style id="wp-custom-css">
/** Start Block Kit CSS:144-3-3a7d335f39a8579c20cdf02f8d462582 **/.envato-block__preview{overflow:visible}/* Envato Kit 141 Custom Styles - Applied to the element under Advanced */.elementor-headline-animation-type-drop-in .elementor-headline-dynamic-wrapper{text-align:center}.envato-kit-141-top-0 h1,.envato-kit-141-top-0 h2,.envato-kit-141-top-0 h3,.envato-kit-141-top-0 h4,.envato-kit-141-top-0 h5,.envato-kit-141-top-0 h6,.envato-kit-141-top-0 p{margin-top:0}.envato-kit-141-newsletter-inline .elementor-field-textual.elementor-size-md{padding-left:1.5rem;padding-right:1.5rem}.envato-kit-141-bottom-0 p{margin-bottom:0}.envato-kit-141-bottom-8 .elementor-price-list .elementor-price-list-item .elementor-price-list-header{margin-bottom:.5rem}.envato-kit-141.elementor-widget-testimonial-carousel.elementor-pagination-type-bullets .swiper-container{padding-bottom:52px}.envato-kit-141-display-inline{display:inline-block}.envato-kit-141 .elementor-slick-slider ul.slick-dots{bottom:-40px}/** End Block Kit CSS:144-3-3a7d335f39a8579c20cdf02f8d462582 **/
</style>
<!-- OceanWP CSS -->
<style type="text/css">
/* CSS Variables */:root{--owp-primary-color:#007a99;--owp-primary-color-hover:#005f78;--owp-link-color:#333333;--owp-link-color-hover:#007a99;--owp-button-bg-color:#007a99;--owp-button-bg-color-hover:#005f78;--owp-button-text-color:#ffffff;--owp-button-text-color-hover:#ffffff;--owp-button-padding-top:14px;--owp-button-padding-right:20px;--owp-button-padding-bottom:14px;--owp-button-padding-left:20px;--owp-button-font-size:14px;--owp-button-font-weight:600;--owp-button-letter-spacing:.05em;--owp-button-line-height:1.2;--owp-button-text-transform:none;--owp-input-text-color:#333333;--owp-input-border-color:#767676;--owp-input-border-color-focus:#333333;--owp-input-font-size:16px;--owp-input-line-height:1.6;--owp-widget-link-color:#007a99;--owp-widget-link-color-hover:#005f78;--owp-widget-link-text-decoration:underline;--owp-widget-link-text-decoration-hover:underline;--owp-widget-link-underline-offset:.2em;--owp-focus-outline-width:2px;--owp-focus-outline-offset:2px;--owp-focus-outline-color:currentColor;--owp-button-focus-outline-color:var(--owp-button-bg-color-hover,var(--owp-button-bg-color,var(--owp-primary-color,currentColor)));--owp-search-form-label-color:#000000;--owp-search-form-label-font-size:inherit;--owp-comment-form-label-color:#000000;--owp-comment-form-label-required-mark-color:#000000;--owp-comment-form-label-font-size:inherit;--owp-social-external-mark-color:#ffffff;--owp-social-external-mark-bg:#000000;--owp-social-external-mark-size:.72em;--owp-social-external-mark-offset-x:-0.15em;--owp-social-external-mark-offset-y:-0.25em;--owp-header-media-button-bg-color:rgba(0,0,0,0.5);--owp-header-media-button-bg-color-hover:rgba(0,0,0,0.75);--owp-header-media-button-bg-color-focus:rgba(0,0,0,0.75);--owp-header-media-button-icon-color:rgba(255,255,255,0.8);--owp-header-media-button-icon-color-hover:#ffffff;--owp-header-media-button-icon-color-focus:#ffffff;--owp-header-media-button-border-color:rgba(255,255,255,0.6);--owp-header-media-button-border-color-hover:rgba(255,255,255,0.9);--owp-header-media-button-border-color-focus:rgba(255,255,255,0.9);--owp-header-media-overlay-color:rgba(0,0,0,0.3);--owp-header-media-height:600px;--owp-header-media-image-position:initial;--owp-header-media-image-size:initial;--owp-topbar-social-external-mark-color:#ffffff;--owp-topbar-social-external-mark-bg:#000000;--owp-topbar-social-external-mark-size:.72em;--owp-topbar-social-external-mark-offset-x:-0.15em;--owp-topbar-social-external-mark-offset-y:-0.25em}@media screen and (max-width:768px){:root{--owp-search-form-label-font-size:inherit;--owp-comment-form-label-font-size:inherit;--owp-header-media-height:600px;--owp-primary-color:#007a99;--owp-primary-color-hover:#005f78;--owp-link-color:#333333;--owp-link-color-hover:#007a99;--owp-button-bg-color:#007a99;--owp-button-bg-color-hover:#005f78;--owp-button-text-color:#ffffff;--owp-button-text-color-hover:#ffffff;--owp-button-padding-top:14px;--owp-button-padding-right:20px;--owp-button-padding-bottom:14px;--owp-button-padding-left:20px;--owp-button-font-size:14px;--owp-button-font-weight:600;--owp-button-letter-spacing:.05em;--owp-button-line-height:1.2;--owp-button-text-transform:none;--owp-input-text-color:#333333;--owp-input-border-color:#767676;--owp-input-border-color-focus:#333333;--owp-input-font-size:16px;--owp-input-line-height:1.6;--owp-widget-link-color:#007a99;--owp-widget-link-color-hover:#005f78;--owp-widget-link-text-decoration:underline;--owp-widget-link-text-decoration-hover:underline;--owp-widget-link-underline-offset:.2em;--owp-focus-outline-width:2px;--owp-focus-outline-offset:2px;--owp-focus-outline-color:currentColor;--owp-button-focus-outline-color:var(--owp-button-bg-color-hover,var(--owp-button-bg-color,var(--owp-primary-color,currentColor)));--owp-search-form-label-color:#000000;--owp-comment-form-label-color:#000000;--owp-comment-form-label-required-mark-color:#000000;--owp-social-external-mark-color:#ffffff;--owp-social-external-mark-bg:#000000;--owp-social-external-mark-size:.72em;--owp-social-external-mark-offset-x:-0.15em;--owp-social-external-mark-offset-y:-0.25em;--owp-header-media-button-bg-color:rgba(0,0,0,0.5);--owp-header-media-button-bg-color-hover:rgba(0,0,0,0.75);--owp-header-media-button-bg-color-focus:rgba(0,0,0,0.75);--owp-header-media-button-icon-color:rgba(255,255,255,0.8);--owp-header-media-button-icon-color-hover:#ffffff;--owp-header-media-button-icon-color-focus:#ffffff;--owp-header-media-button-border-color:rgba(255,255,255,0.6);--owp-header-media-button-border-color-hover:rgba(255,255,255,0.9);--owp-header-media-button-border-color-focus:rgba(255,255,255,0.9);--owp-header-media-overlay-color:rgba(0,0,0,0.3);--owp-header-media-image-position:initial;--owp-header-media-image-size:initial;--owp-topbar-social-external-mark-color:#ffffff;--owp-topbar-social-external-mark-bg:#000000;--owp-topbar-social-external-mark-size:.72em;--owp-topbar-social-external-mark-offset-x:-0.15em;--owp-topbar-social-external-mark-offset-y:-0.25em}}@media screen and (max-width:480px){:root{--owp-search-form-label-font-size:inherit;--owp-comment-form-label-font-size:inherit;--owp-header-media-height:600px;--owp-primary-color:#007a99;--owp-primary-color-hover:#005f78;--owp-link-color:#333333;--owp-link-color-hover:#007a99;--owp-button-bg-color:#007a99;--owp-button-bg-color-hover:#005f78;--owp-button-text-color:#ffffff;--owp-button-text-color-hover:#ffffff;--owp-button-padding-top:14px;--owp-button-padding-right:20px;--owp-button-padding-bottom:14px;--owp-button-padding-left:20px;--owp-button-font-size:14px;--owp-button-font-weight:600;--owp-button-letter-spacing:.05em;--owp-button-line-height:1.2;--owp-button-text-transform:none;--owp-input-text-color:#333333;--owp-input-border-color:#767676;--owp-input-border-color-focus:#333333;--owp-input-font-size:16px;--owp-input-line-height:1.6;--owp-widget-link-color:#007a99;--owp-widget-link-color-hover:#005f78;--owp-widget-link-text-decoration:underline;--owp-widget-link-text-decoration-hover:underline;--owp-widget-link-underline-offset:.2em;--owp-focus-outline-width:2px;--owp-focus-outline-offset:2px;--owp-focus-outline-color:currentColor;--owp-button-focus-outline-color:var(--owp-button-bg-color-hover,var(--owp-button-bg-color,var(--owp-primary-color,currentColor)));--owp-search-form-label-color:#000000;--owp-comment-form-label-color:#000000;--owp-comment-form-label-required-mark-color:#000000;--owp-social-external-mark-color:#ffffff;--owp-social-external-mark-bg:#000000;--owp-social-external-mark-size:.72em;--owp-social-external-mark-offset-x:-0.15em;--owp-social-external-mark-offset-y:-0.25em;--owp-header-media-button-bg-color:rgba(0,0,0,0.5);--owp-header-media-button-bg-color-hover:rgba(0,0,0,0.75);--owp-header-media-button-bg-color-focus:rgba(0,0,0,0.75);--owp-header-media-button-icon-color:rgba(255,255,255,0.8);--owp-header-media-button-icon-color-hover:#ffffff;--owp-header-media-button-icon-color-focus:#ffffff;--owp-header-media-button-border-color:rgba(255,255,255,0.6);--owp-header-media-button-border-color-hover:rgba(255,255,255,0.9);--owp-header-media-button-border-color-focus:rgba(255,255,255,0.9);--owp-header-media-overlay-color:rgba(0,0,0,0.3);--owp-header-media-image-position:initial;--owp-header-media-image-size:initial;--owp-topbar-social-external-mark-color:#ffffff;--owp-topbar-social-external-mark-bg:#000000;--owp-topbar-social-external-mark-size:.72em;--owp-topbar-social-external-mark-offset-x:-0.15em;--owp-topbar-social-external-mark-offset-y:-0.25em}}/* Colors */body .theme-button,body input[type="submit"],body button[type="submit"],body button,body .button,body div.wpforms-container-full .wpforms-form input[type=submit],body div.wpforms-container-full .wpforms-form button[type=submit],body div.wpforms-container-full .wpforms-form .wpforms-page-button,.woocommerce-cart .wp-element-button,.woocommerce-checkout .wp-element-button,.wp-block-button__link{border-color:#ffffff}body .theme-button:hover,body input[type="submit"]:hover,body button[type="submit"]:hover,body button:hover,body .button:hover,body div.wpforms-container-full .wpforms-form input[type=submit]:hover,body div.wpforms-container-full .wpforms-form input[type=submit]:active,body div.wpforms-container-full .wpforms-form button[type=submit]:hover,body div.wpforms-container-full .wpforms-form button[type=submit]:active,body div.wpforms-container-full .wpforms-form .wpforms-page-button:hover,body div.wpforms-container-full .wpforms-form .wpforms-page-button:active,.woocommerce-cart .wp-element-button:hover,.woocommerce-checkout .wp-element-button:hover,.wp-block-button__link:hover{border-color:#ffffff}/* OceanWP Style Settings CSS */.theme-button,input[type="submit"],button[type="submit"],button,.button,body div.wpforms-container-full .wpforms-form input[type=submit],body div.wpforms-container-full .wpforms-form button[type=submit],body div.wpforms-container-full .wpforms-form .wpforms-page-button{border-style:solid}.theme-button,input[type="submit"],button[type="submit"],button,.button,body div.wpforms-container-full .wpforms-form input[type=submit],body div.wpforms-container-full .wpforms-form button[type=submit],body div.wpforms-container-full .wpforms-form .wpforms-page-button{border-width:1px}form input[type="text"],form input[type="password"],form input[type="email"],form input[type="url"],form input[type="date"],form input[type="month"],form input[type="time"],form input[type="datetime"],form input[type="datetime-local"],form input[type="week"],form input[type="number"],form input[type="search"],form input[type="tel"],form input[type="color"],form select,form textarea,.woocommerce .woocommerce-checkout .select2-container--default .select2-selection--single{border-style:solid}body div.wpforms-container-full .wpforms-form input[type=date],body div.wpforms-container-full .wpforms-form input[type=datetime],body div.wpforms-container-full .wpforms-form input[type=datetime-local],body div.wpforms-container-full .wpforms-form input[type=email],body div.wpforms-container-full .wpforms-form input[type=month],body div.wpforms-container-full .wpforms-form input[type=number],body div.wpforms-container-full .wpforms-form input[type=password],body div.wpforms-container-full .wpforms-form input[type=range],body div.wpforms-container-full .wpforms-form input[type=search],body div.wpforms-container-full .wpforms-form input[type=tel],body div.wpforms-container-full .wpforms-form input[type=text],body div.wpforms-container-full .wpforms-form input[type=time],body div.wpforms-container-full .wpforms-form input[type=url],body div.wpforms-container-full .wpforms-form input[type=week],body div.wpforms-container-full .wpforms-form select,body div.wpforms-container-full .wpforms-form textarea{border-style:solid}form input[type="text"],form input[type="password"],form input[type="email"],form input[type="url"],form input[type="date"],form input[type="month"],form input[type="time"],form input[type="datetime"],form input[type="datetime-local"],form input[type="week"],form input[type="number"],form input[type="search"],form input[type="tel"],form input[type="color"],form select,form textarea{border-radius:3px}body div.wpforms-container-full .wpforms-form input[type=date],body div.wpforms-container-full .wpforms-form input[type=datetime],body div.wpforms-container-full .wpforms-form input[type=datetime-local],body div.wpforms-container-full .wpforms-form input[type=email],body div.wpforms-container-full .wpforms-form input[type=month],body div.wpforms-container-full .wpforms-form input[type=number],body div.wpforms-container-full .wpforms-form input[type=password],body div.wpforms-container-full .wpforms-form input[type=range],body div.wpforms-container-full .wpforms-form input[type=search],body div.wpforms-container-full .wpforms-form input[type=tel],body div.wpforms-container-full .wpforms-form input[type=text],body div.wpforms-container-full .wpforms-form input[type=time],body div.wpforms-container-full .wpforms-form input[type=url],body div.wpforms-container-full .wpforms-form input[type=week],body div.wpforms-container-full .wpforms-form select,body div.wpforms-container-full .wpforms-form textarea{border-radius:3px}/* Header */#site-header.has-header-media .overlay-header-media{background-color:rgba(0,0,0,0.5)}/* Blog CSS */.ocean-single-post-header ul.meta-item li a:hover{color:#333333}/* Sidebar */.widget-area .sidebar-box,.separate-layout .sidebar-box{margin-bottom:px}/* Typography */body{font-size:14px;line-height:1.8}h1,h2,h3,h4,h5,h6,.theme-heading,.widget-title,.oceanwp-widget-recent-posts-title,.comment-reply-title,.entry-title,.sidebar-box .widget-title{line-height:1.4}h1{font-size:23px;line-height:1.4}h2{font-size:20px;line-height:1.4}h3{font-size:18px;line-height:1.4}h4{font-size:17px;line-height:1.4}h5{font-size:14px;line-height:1.4}h6{font-size:15px;line-height:1.4}.page-header .page-header-title,.page-header.background-image-page-header .page-header-title{font-size:32px;line-height:1.4}.page-header .page-subheading,.page-header.background-image-page-header .page-subheading{font-size:15px;line-height:1.8}.site-breadcrumbs,.site-breadcrumbs a{font-size:13px;line-height:1.4}#top-bar-content,#top-bar-social-alt{font-size:12px;line-height:1.8}#site-logo a.site-logo-text{font-size:24px;line-height:1.8}.dropdown-menu ul li a.menu-link,#site-header.full_screen-header .fs-dropdown-menu ul.sub-menu li a{font-size:12px;line-height:1.2;letter-spacing:.6px}.sidr-class-dropdown-menu li a,a.sidr-class-toggle-sidr-close,button.sidr-class-toggle-sidr-close,#mobile-dropdown ul li a,#mobile-dropdown ul li >button.menu-link.dropdown-toggle,body #mobile-fullscreen ul li a,body #mobile-fullscreen ul li >button.menu-link.dropdown-toggle{font-size:15px;line-height:1.8}.blog-entry.post .blog-entry-header .entry-title a{font-size:24px;line-height:1.4}.ocean-single-post-header .single-post-title{font-size:34px;line-height:1.4;letter-spacing:.6px}.ocean-single-post-header ul.meta-item li,.ocean-single-post-header ul.meta-item li a{font-size:13px;line-height:1.4;letter-spacing:.6px}.ocean-single-post-header .post-author-name,.ocean-single-post-header .post-author-name a{font-size:14px;line-height:1.4;letter-spacing:.6px}.ocean-single-post-header .post-author-description{font-size:12px;line-height:1.4;letter-spacing:.6px}.single-post .entry-title{line-height:1.4;letter-spacing:.6px}.single-post ul.meta li,.single-post ul.meta li a{font-size:14px;line-height:1.4;letter-spacing:.6px}.sidebar-box .widget-title,.sidebar-box.widget_block .wp-block-heading{font-size:13px;line-height:1;letter-spacing:1px}#footer-widgets .footer-box .widget-title{font-size:13px;line-height:1;letter-spacing:1px}#footer-bottom #copyright{font-size:12px;line-height:1}#footer-bottom #footer-bottom-menu{font-size:12px;line-height:1}.ocean-preloader--active .preloader-after-content{font-size:20px;line-height:1.8;letter-spacing:.6px}
</style>	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"><script src="./scripts/background-slideshow.min.js" async></script><script src="./scripts/background-video.min.js" async></script><script src="./scripts/image-carousel.min.js" async></script><script src="./scripts/wp-emoji-release.min.js" defer></script><style id="custom-escort-cards-css">
/* ============================================================
   GLOBAL MOBILE OVERFLOW FIX
   ============================================================ */
html, body {
    overflow-x: hidden !important;
    width: 100%;
}
*, *::before, *::after { box-sizing: border-box; }
.elementor {
    width: 100% !important;
    max-width: 100% !important;
    overflow-x: hidden !important;
}
.e-con-boxed,
.elementor-section.elementor-section-boxed > .elementor-container {
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
}
.e-con-boxed > .e-con-inner {
    width: 100% !important;
    max-width: 100% !important;
    padding-left: 15px !important;
    padding-right: 15px !important;
}
.e-con, .e-flex, .e-con-full {
    width: 100% !important;
    max-width: 100% !important;
}
img { max-width: 100%; height: auto; }
.elementor-widget-container,
.elementor-column,
.elementor-column-wrap,
.elementor-widget-wrap {
    max-width: 100% !important;
    overflow-x: hidden;
}
@media (max-width: 767px) {
    .e-transform { transform: none !important; }
    .elementor-image img,
    .elementor-widget-image img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
    }
    .e-con-boxed > .e-con-inner {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
}

.escort-cards-container {
    width: 100%;
    max-width: 1200px;
    margin: 15px auto 40px auto;
    padding: 0 15px;
    box-sizing: border-box;
}
.escort-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    width: 100%;
}
.escort-item-card {
    position: relative;
    display: block;
    width: 100%;
    height: 480px;
    border-radius: 40px;
    overflow: hidden;
    text-decoration: none;
    background-color: #121212;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.45);
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    cursor: pointer;
    -webkit-tap-highlight-color: transparent;
}
.escort-item-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.7);
}
.escort-item-card .escort-img-box {
    width: 100%;
    height: 100%;
    overflow: hidden;
    border-radius: 40px;
}
.escort-item-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    border-radius: 40px;
    transition: filter 0.6s ease, transform 0.6s ease;
}
.escort-item-card:hover img {
    filter: brightness(35%);
    transform: scale(1.03);
}
.escort-card-name {
    position: absolute;
    top: 26px;
    left: 28px;
    font-family: "Montserrat", sans-serif;
    font-size: 22px;
    font-weight: 500;
    text-transform: uppercase;
    line-height: 1.2;
    letter-spacing: 1.4px;
    color: #FFFFFF;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
    z-index: 2;
    margin: 0;
    padding: 0;
    pointer-events: none;
}
.escort-card-button {
    position: absolute;
    bottom: 24px;
    right: 28px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #FFFFFF;
    font-family: "Montserrat", sans-serif;
    font-size: 18px;
    font-weight: 400;
    text-transform: capitalize;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
    z-index: 2;
    pointer-events: none;
}
.escort-card-button svg {
    width: 20px;
    height: 20px;
    fill: #FFFFFF;
    transition: transform 0.3s ease;
}
.escort-item-card:hover .escort-card-button svg {
    transform: translateX(5px);
}

/* No sticky hover on touch screens */
@media (hover: none) {
    .escort-item-card:hover { transform: none; }
    .escort-item-card:hover img { filter: none; transform: none; }
}

/* ---------- TABLET (991px) ---------- */
@media (max-width: 991px) {
    .escort-cards-container {
        padding: 0 12px;
    }
    .escort-cards-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .escort-item-card {
        height: 400px;
        border-radius: 30px;
    }
    .escort-item-card .escort-img-box,
    .escort-item-card img {
        border-radius: 30px;
    }
    .escort-card-name {
        font-size: 18px;
        top: 20px;
        left: 20px;
    }
    .escort-card-button {
        font-size: 15px;
        bottom: 18px;
        right: 18px;
    }
}

/* ---------- MOBILE (575px) ---------- */
@media (max-width: 575px) {
    .escort-cards-container {
        padding: 0 10px;
    }
    .escort-cards-grid {
        grid-template-columns: 1fr;
        gap: 18px;
    }
    .escort-item-card {
        height: 360px;
        border-radius: 24px;
    }
    .escort-item-card .escort-img-box,
    .escort-item-card img {
        border-radius: 24px;
    }
    .escort-card-name {
        font-size: 16px;
        letter-spacing: 1px;
        top: 18px;
        left: 18px;
    }
    .escort-card-button {
        font-size: 13px;
        bottom: 16px;
        right: 16px;
        gap: 7px;
    }
    .escort-card-button svg {
        width: 16px;
        height: 16px;
    }
}

/* ---------- SMALL MOBILE (400px) ---------- */
@media (max-width: 400px) {
    .escort-item-card {
        height: 320px;
        border-radius: 20px;
    }
    .escort-item-card .escort-img-box,
    .escort-item-card img {
        border-radius: 20px;
    }
    .escort-card-name {
        font-size: 14px;
        top: 14px;
        left: 14px;
    }
    .escort-card-button {
        font-size: 12px;
        bottom: 12px;
        right: 14px;
    }
}
</style>
</head>
<body class="wp-singular page-template page-template-elementor_canvas page page-id-1205 wp-embed-responsive wp-theme-oceanwp oceanwp-theme dropdown-mobile default-breakpoint has-sidebar content-right-sidebar has-topbar has-breadcrumbs elementor-default elementor-template-canvas elementor-kit-7 elementor-page elementor-page-1205 e--ua-blink e--ua-chrome e--ua-webkit" data-elementor-device-mode="tablet">
			<div data-elementor-type="wp-page" data-elementor-id="1205" class="elementor elementor-1205">
				<div class="elementor-element elementor-element-1941fcc elementor-hidden-mobile e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="1941fcc" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-6965c8f e-con-full e-flex e-con e-child" data-id="6965c8f" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-d9e2971 elementor-widget elementor-widget-image" data-id="d9e2971" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<a href="../index.php"><img decoding="async" src="./images/Screenshot-2026-05-04-at-4.02.44-PM.png" title="Screenshot 2026-05-04 at 4.02.44 PM" alt="Screenshot 2026-05-04 at 4.02.44 PM" loading="lazy"></a>															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-a5e24a6 e-con-full e-flex e-con e-child" data-id="a5e24a6" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-ffcd341 elementor-shape-rounded elementor-grid-0 e-grid-align-center elementor-widget elementor-widget-social-icons" data-id="ffcd341" data-element_type="widget" data-e-type="widget" data-widget_type="social-icons.default">
				<div class="elementor-widget-container">
							<div class="elementor-social-icons-wrapper elementor-grid" role="list">
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-whatsapp elementor-repeater-item-623db3a" target="_blank">
						<span class="elementor-screen-only">Whatsapp</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-whatsapp" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" /></svg>					</a>
				</span>
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-telegram elementor-repeater-item-3167f5e" target="_blank">
						<span class="elementor-screen-only">Telegram</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-telegram" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z" /></svg>					</a>
				</span>
					</div>
						</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-90b8599 e-con-full e-flex e-con e-child" data-id="90b8599" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-f6db973 e-transform elementor-align-center elementor-widget elementor-widget-button" data-id="f6db973" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_transform_translateX_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:40,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:19,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="#contact">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">PICK UP</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-5618bab e-con-full e-flex e-con e-child" data-id="5618bab" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-d2136d2 e-transform elementor-widget elementor-widget-button" data-id="d2136d2" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_transform_translateX_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:40,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:19,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="<?= !empty($model['whatsapp']) ? e($model['whatsapp']) : ('https://wa.me/61489987819?text=' . urlencode('Hello, I would like to book ' . $model['name'])) ?>">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">BECOME A MODEL</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-76ae831 elementor-hidden-desktop e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="76ae831" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-efb2b21 elementor-widget elementor-widget-spacer" data-id="efb2b21" data-element_type="widget" data-e-type="widget" data-widget_type="spacer.default">
				<div class="elementor-widget-container">
							<div class="elementor-spacer">
			<div class="elementor-spacer-inner"></div>
		</div>
						</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-ba4cfd0 elementor-hidden-desktop e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="ba4cfd0" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-de26296 elementor-widget elementor-widget-image" data-id="de26296" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<a href="../index.php"><img fetchpriority="high" decoding="async" width="610" height="372" src="./images/Screenshot-2026-05-04-at-4.02.44-PM.png" class="attachment-large size-large wp-image-1814" alt="" srcset="https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM.png 610w, https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM-300x183.png 300w" sizes="(max-width: 610px) 100vw, 610px"></a>															</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-b1c2ecb elementor-hidden-desktop e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="b1c2ecb" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-c4230c8 elementor-grid-mobile-0 e-grid-align-mobile-center elementor-shape-rounded elementor-grid-0 e-grid-align-center elementor-widget elementor-widget-social-icons" data-id="c4230c8" data-element_type="widget" data-e-type="widget" data-widget_type="social-icons.default">
				<div class="elementor-widget-container">
							<div class="elementor-social-icons-wrapper elementor-grid" role="list">
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-whatsapp elementor-repeater-item-be3dcfe" target="_blank">
						<span class="elementor-screen-only">Whatsapp</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-whatsapp" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" /></svg>					</a>
				</span>
							<span class="elementor-grid-item" role="listitem">
					<a class="elementor-icon elementor-social-icon elementor-social-icon-telegram elementor-repeater-item-a436ceb" target="_blank">
						<span class="elementor-screen-only">Telegram</span>
						<svg aria-hidden="true" class="e-font-icon-svg e-fab-telegram" viewBox="0 0 496 512" xmlns="http://www.w3.org/2000/svg"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z" /></svg>					</a>
				</span>
					</div>
						</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-b13704c e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="b13704c" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-9c428b8 e-transform elementor-hidden-desktop elementor-widget elementor-widget-button" data-id="9c428b8" data-element_type="widget" data-e-type="widget" data-settings="{&quot;_transform_translateX_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:40,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:19,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateX_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]},&quot;_transform_translateY_effect_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:&quot;&quot;,&quot;sizes&quot;:[]}}" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="#contact">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">PICK UP</span>
					</span>
					</a>
				</div>
								</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-fbae335 elementor-hidden-desktop e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="fbae335" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
					</div>
				</div>
		<div class="elementor-element elementor-element-2a64c3c e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="2a64c3c" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-4d78d64 e-con-full e-flex e-con e-child" data-id="4d78d64" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-c56af38 elementor-arrows-position-inside elementor-widget elementor-widget-image-carousel e-widget-swiper" data-id="c56af38" data-element_type="widget" data-e-type="widget" data-settings="{&quot;slides_to_show&quot;:&quot;2&quot;,&quot;slides_to_scroll&quot;:&quot;2&quot;,&quot;navigation&quot;:&quot;arrows&quot;,&quot;slides_to_show_mobile&quot;:&quot;2&quot;,&quot;slides_to_scroll_mobile&quot;:&quot;2&quot;,&quot;autoplay&quot;:&quot;yes&quot;,&quot;pause_on_hover&quot;:&quot;yes&quot;,&quot;pause_on_interaction&quot;:&quot;yes&quot;,&quot;autoplay_speed&quot;:5000,&quot;infinite&quot;:&quot;yes&quot;,&quot;speed&quot;:500}" data-widget_type="image-carousel.default">
				<div class="elementor-widget-container">
							<div class="elementor-image-carousel-wrapper swiper" role="region" aria-roledescription="carousel" aria-label="Image Carousel" dir="ltr">
    <div class="elementor-image-carousel swiper-wrapper swiper-image-stretch">
        <?php foreach ($gallery as $gIdx => $gImg): ?>
            <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="<?= ($gIdx + 1) ?> / <?= count($gallery) ?>">
                <figure class="swiper-slide-inner">
                    <img decoding="async" class="swiper-slide-image" src="<?= e(get_image_url($gImg, true)) ?>" alt="<?= e($model['name']) ?>">
                </figure>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="elementor-swiper-button elementor-swiper-button-prev" role="button" tabindex="0" aria-label="Previous slide">
        <svg aria-hidden="true" class="e-font-icon-svg e-eicon-chevron-left" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M646 125C629 125 613 133 604 142L308 442C296 454 292 471 292 487 292 504 296 521 308 533L604 854C617 867 629 875 646 875 663 875 679 871 692 858 704 846 713 829 713 812 713 796 708 779 692 767L438 487 692 225C700 217 708 204 708 187 708 171 704 154 692 142 675 129 663 125 646 125Z" /></svg>
    </div>
    <div class="elementor-swiper-button elementor-swiper-button-next" role="button" tabindex="0" aria-label="Next slide">
        <svg aria-hidden="true" class="e-font-icon-svg e-eicon-chevron-right" viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg"><path d="M696 533C708 521 713 504 713 487 713 471 708 454 696 446L400 146C388 133 375 125 354 125 338 125 325 129 313 142 300 154 292 171 292 187 292 204 296 221 308 233L563 492 304 771C292 783 288 800 288 817 288 833 296 850 308 863 321 871 338 875 354 875 371 875 388 867 400 854L696 533Z" /></svg>
    </div>
    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
</div>
</div>
</div>
</div>
<div class="elementor-element elementor-element-08ad2ad e-con-full e-flex e-con e-child" data-id="08ad2ad" data-element_type="container" data-e-type="container">
				<div class="elementor-element elementor-element-a878f63 elementor-widget elementor-widget-heading" data-id="a878f63" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default"><?= strtoupper(e($model['name'])) ?></h2>				</div>
				</div>
				<div class="elementor-element elementor-element-154e590 elementor-widget elementor-widget-heading" data-id="154e590" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Age: <?= e($model['age']) ?></h2>				</div>
				</div>
				<div class="elementor-element elementor-element-443e587 elementor-widget elementor-widget-heading" data-id="443e587" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Height: <?= e($model['height']) ?></h2>				</div>
				</div>
				<div class="elementor-element elementor-element-d050b08 elementor-widget elementor-widget-heading" data-id="d050b08" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Weight: <?= e($model['weight']) ?></h2>				</div>
				</div>
				<div class="elementor-element elementor-element-4dd7a80 elementor-widget elementor-widget-heading" data-id="4dd7a80" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default"><?= $model['gender'] === 'boy' ? 'Chest' : 'Breast' ?> size: <?= e($model['breast_size']) ?></h2>				</div>
				</div>
				<div class="elementor-element elementor-element-1100fce elementor-widget elementor-widget-heading" data-id="1100fce" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">Hair: <?= e($model['hair']) ?></h2>				</div>
				</div>
				<div class="elementor-element elementor-element-35b6a2f elementor-widget elementor-widget-heading" data-id="35b6a2f" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default"><?= !empty($model['bio']) ? nl2br(e($model['bio'])) : 'Would you like to spend time <br>with an elite model?' ?></h2>				</div>
				</div>
				<div class="elementor-element elementor-element-a2688ed elementor-widget elementor-widget-button" data-id="a2688ed" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-long-arrow-alt-right" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" /></svg>			</span>
									<span class="elementor-button-text">CONTACT THE MANAGER</span> target="_blank"
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-bfdf5de e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="bfdf5de" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-b74a4e0 elementor-widget elementor-widget-spacer" data-id="b74a4e0" data-element_type="widget" data-e-type="widget" data-widget_type="spacer.default">
				<div class="elementor-widget-container">
							<div class="elementor-spacer">
			<div class="elementor-spacer-inner"></div>
		</div>
						</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-1c406f6 e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="1c406f6" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-b4378d9 elementor-widget elementor-widget-heading" data-id="b4378d9" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">More <?= $model['gender'] === 'boy' ? 'Boys' : 'Girls' ?></h2>				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-055f661 e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="055f661" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-0d28b9a elementor-widget elementor-widget-spacer" data-id="0d28b9a" data-element_type="widget" data-e-type="widget" data-widget_type="spacer.default">
				<div class="elementor-widget-container">
							<div class="elementor-spacer">
			<div class="elementor-spacer-inner"></div>
		</div>
						</div>
				</div>
					</div>
				</div>
		<div class="escort-cards-container">
    <div class="escort-cards-grid">
        <?php foreach ($moreModels as $other): ?>
        <a href="index.php?id=<?= $other['id'] ?>" class="escort-item-card">
            <div class="escort-img-box">
                <img decoding="async" src="<?= e(get_image_url($other['main_image'], true)) ?>" alt="<?= e($other['name']) ?>" loading="lazy">
            </div>
            <div class="escort-card-name"><?= e($other['name']) ?></div>
            <div class="escort-card-button">
                <span>View Profile</span>
                <svg aria-hidden="true" class="e-font-icon-svg e-fas-long-arrow-alt-right" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M313.941 216H12c-6.627 0-12 5.373-12 12v56c0 6.627 5.373 12 12 12h301.941v46.059c0 21.382 25.851 32.09 40.971 16.971l86.059-86.059c9.373-9.373 9.373-24.569 0-33.941l-86.059-86.059c-15.119-15.119-40.971-4.411-40.971 16.971V216z" fill="#fff"/></svg>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
 
<div class="elementor-element elementor-element-e0048ee elementor-hidden-mobile e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="e0048ee" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-c6d0463 elementor-widget elementor-widget-menu-anchor" data-id="c6d0463" data-element_type="widget" data-e-type="widget" data-widget_type="menu-anchor.default">
				<div class="elementor-widget-container">
							<div class="elementor-menu-anchor" id="contact"></div>
						</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-4189606 elementor-hidden-mobile e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="4189606" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-7734f5a e-con-full e-flex e-con e-child" data-id="7734f5a" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-0af01c5 elementor-widget elementor-widget-heading" data-id="0af01c5" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">how to contact us</h2>				</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-cca1aa9 elementor-hidden-mobile e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="cca1aa9" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-0c6cee9 e-con-full e-flex e-con e-child" data-id="0c6cee9" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-8d3fef9 elementor-widget elementor-widget-image" data-id="8d3fef9" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<a href="../index.php"><img fetchpriority="high" decoding="async" width="610" height="372" src="./images/Screenshot-2026-05-04-at-4.02.44-PM.png" class="attachment-large size-large wp-image-1814" alt="" srcset="https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM.png 610w, https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM-300x183.png 300w" sizes="(max-width: 610px) 100vw, 610px"></a>															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-0ad0571 e-con-full e-flex e-con e-child" data-id="0ad0571" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-332cd6e elementor-widget elementor-widget-heading" data-id="332cd6e" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">© 2025 Euroescortbangkok</h2>				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-5e9e166 e-con-full e-flex e-con e-child" data-id="5e9e166" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-49bf90c elementor-widget elementor-widget-button" data-id="49bf90c" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fab-whatsapp" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" /></svg>			</span>
									<span class="elementor-button-text">WHATSAPP</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-0dad96c e-con-full e-flex e-con e-child" data-id="0dad96c" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-8a61c78 elementor-widget elementor-widget-button" data-id="8a61c78" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fab-telegram-plane" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z" /></svg>			</span>
									<span class="elementor-button-text">TELEGRAM</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-122da0c elementor-hidden-desktop e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="122da0c" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-df2bd4c elementor-widget elementor-widget-menu-anchor" data-id="df2bd4c" data-element_type="widget" data-e-type="widget" data-widget_type="menu-anchor.default">
				<div class="elementor-widget-container">
							<div class="elementor-menu-anchor" id="girls"></div>
						</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-5ffa510 elementor-hidden-desktop e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="5ffa510" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-0f68327 e-con-full e-flex e-con e-child" data-id="0f68327" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-95eb65f elementor-widget elementor-widget-heading" data-id="95eb65f" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">How To Contact Us</h2>				</div>
				</div>
				</div>
					</div>
				</div>
		<div class="elementor-element elementor-element-8fa68c5 elementor-hidden-desktop e-flex e-con-boxed e-con e-parent e-lazyloaded" data-id="8fa68c5" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="e-con-inner">
		<div class="elementor-element elementor-element-e4a5aee e-con-full e-flex e-con e-child" data-id="e4a5aee" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-7cfa498 elementor-widget elementor-widget-image" data-id="7cfa498" data-element_type="widget" data-e-type="widget" data-widget_type="image.default">
				<div class="elementor-widget-container">
															<a href="../index.php"><img fetchpriority="high" decoding="async" width="610" height="372" src="./images/Screenshot-2026-05-04-at-4.02.44-PM.png" class="attachment-large size-large wp-image-1814" alt="" srcset="https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM.png 610w, https://euroescortbangkok.com/wp-content/uploads/2026/05/Screenshot-2026-05-04-at-4.02.44-PM-300x183.png 300w" sizes="(max-width: 610px) 100vw, 610px"></a>															</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-54f4ec7 e-con-full e-flex e-con e-child" data-id="54f4ec7" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-cceb5da elementor-widget elementor-widget-heading" data-id="cceb5da" data-element_type="widget" data-e-type="widget" data-widget_type="heading.default">
				<div class="elementor-widget-container">
					<h2 class="elementor-heading-title elementor-size-default">© 2025 Euroescortbangkok</h2>				</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-d4054e3 e-con-full e-flex e-con e-child" data-id="d4054e3" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-610b643 elementor-mobile-align-center elementor-widget elementor-widget-button" data-id="610b643" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fab-whatsapp" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z" /></svg>			</span>
									<span class="elementor-button-text">WHATSAPP</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				</div>
		<div class="elementor-element elementor-element-a81e51f e-con-full e-flex e-con e-child" data-id="a81e51f" data-element_type="container" data-e-type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
				<div class="elementor-element elementor-element-6e412a5 elementor-mobile-align-center elementor-widget elementor-widget-button" data-id="6e412a5" data-element_type="widget" data-e-type="widget" data-widget_type="button.default">
				<div class="elementor-widget-container">
									<div class="elementor-button-wrapper">
					<a class="elementor-button elementor-button-link elementor-size-sm" href="#">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon">
				<svg aria-hidden="true" class="e-font-icon-svg e-fab-telegram-plane" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M446.7 98.6l-67.6 318.8c-5.1 22.5-18.4 28.1-37.3 17.5l-103-75.9-49.7 47.8c-5.5 5.5-10.1 10.1-20.7 10.1l7.4-104.9 190.9-172.5c8.3-7.4-1.8-11.5-12.9-4.1L117.8 284 16.2 252.2c-22.1-6.9-22.5-22.1 4.6-32.7L418.2 66.4c18.4-6.9 34.5 4.1 28.5 32.2z" /></svg>			</span>
									<span class="elementor-button-text">TELEGRAM</span>
					</span>
					</a>
				</div>
								</div>
				</div>
				<div class="elementor-element elementor-element-7c9823b elementor-widget elementor-widget-spacer" data-id="7c9823b" data-element_type="widget" data-e-type="widget" data-widget_type="spacer.default">
				<div class="elementor-widget-container">
							<div class="elementor-spacer">
			<div class="elementor-spacer-inner"></div>
		</div>
						</div>
				</div>
				</div>
					</div>
				</div>
				</div>
		<script type="speculationrules">
{"prefetch":[{"source":"document","where":{"and":[{"href_matches":"/*"},{"not":{"href_matches":["/wp-*.php","/wp-admin/*","/wp-content/uploads/*","/wp-content/*","/wp-content/plugins/*","/wp-content/themes/oceanwp/*","/*\\?(.+)"]}},{"not":{"selector_matches":"a[rel~=\"nofollow\"]"}},{"not":{"selector_matches":".no-prefetch, .no-prefetch a"}}]},"eagerness":"conservative"}]}
</script>
		<!-- Click to Chat - https://holithemes.com/plugins/click-to-chat/  v4.44 -->
			<style id="ht-ctc-entry-animations">.ht_ctc_entry_animation{animation-duration:0.4s;animation-fill-mode:both;animation-delay:0s;animation-iteration-count:1;}			@keyframes ht_ctc_anim_corner {0% {opacity: 0;transform: scale(0);}100% {opacity: 1;transform: scale(1);}}.ht_ctc_an_entry_corner {animation-name: ht_ctc_anim_corner;animation-timing-function: cubic-bezier(0.25, 1, 0.5, 1);transform-origin: bottom var(--side, right);}
			</style>						<div class="ht-ctc ht-ctc-chat ctc-analytics ctc_wp_desktop style-2 ht_ctc_entry_animation ht_ctc_an_entry_corner ht_ctc_animation no-animation" id="ht-ctc-chat" style="position: fixed; bottom: 15px; right: 15px; cursor: pointer; z-index: 99999999; --side: right;">
												<div class="ht_ctc_style ht_ctc_chat_style">
								<div style="display: flex; justify-content: center; align-items: center;  " class="ctc-analytics ctc_s_2">
	<p class="ctc-analytics ctc_cta ctc_cta_stick ht-ctc-cta ht-ctc-cta-hover" style="padding: 0px 16px; line-height: 1.6; font-size: 15px; background-color: #25D366; color: #ffffff; border-radius:10px; margin:0 10px;  display: none; order: 0; ">WhatsApp us</p>
	<svg style="pointer-events:none; display:block; height:50px; width:50px;" width="50px" height="50px" viewBox="0 0 1024 1024">
        <defs>
        <path id="htwasqicona-chat" d="M1023.941 765.153c0 5.606-.171 17.766-.508 27.159-.824 22.982-2.646 52.639-5.401 66.151-4.141 20.306-10.392 39.472-18.542 55.425-9.643 18.871-21.943 35.775-36.559 50.364-14.584 14.56-31.472 26.812-50.315 36.416-16.036 8.172-35.322 14.426-55.744 18.549-13.378 2.701-42.812 4.488-65.648 5.3-9.402.336-21.564.505-27.15.505l-504.226-.081c-5.607 0-17.765-.172-27.158-.509-22.983-.824-52.639-2.646-66.152-5.4-20.306-4.142-39.473-10.392-55.425-18.542-18.872-9.644-35.775-21.944-50.364-36.56-14.56-14.584-26.812-31.471-36.415-50.314-8.174-16.037-14.428-35.323-18.551-55.744-2.7-13.378-4.487-42.812-5.3-65.649-.334-9.401-.503-21.563-.503-27.148l.08-504.228c0-5.607.171-17.766.508-27.159.825-22.983 2.646-52.639 5.401-66.151 4.141-20.306 10.391-39.473 18.542-55.426C34.154 93.24 46.455 76.336 61.07 61.747c14.584-14.559 31.472-26.812 50.315-36.416 16.037-8.172 35.324-14.426 55.745-18.549 13.377-2.701 42.812-4.488 65.648-5.3 9.402-.335 21.565-.504 27.149-.504l504.227.081c5.608 0 17.766.171 27.159.508 22.983.825 52.638 2.646 66.152 5.401 20.305 4.141 39.472 10.391 55.425 18.542 18.871 9.643 35.774 21.944 50.363 36.559 14.559 14.584 26.812 31.471 36.415 50.315 8.174 16.037 14.428 35.323 18.551 55.744 2.7 13.378 4.486 42.812 5.3 65.649.335 9.402.504 21.564.504 27.15l-.082 504.226z" />
        </defs>
        <lineargradient id="htwasqiconb-chat" gradientUnits="userSpaceOnUse" x1="512.001" y1=".978" x2="512.001" y2="1025.023">
            <stop offset="0" stop-color="#61fd7d" />
            <stop offset="1" stop-color="#2bb826" />
        </lineargradient>
        <use xlink:href="#htwasqicona-chat" overflow="visible" style="fill: url(#htwasqiconb-chat)" fill="url(#htwasqiconb-chat)" />
        <g>
            <path style="fill: #FFFFFF;" fill="#FFF" d="M783.302 243.246c-69.329-69.387-161.529-107.619-259.763-107.658-202.402 0-367.133 164.668-367.214 367.072-.026 64.699 16.883 127.854 49.017 183.522l-52.096 190.229 194.665-51.047c53.636 29.244 114.022 44.656 175.482 44.682h.151c202.382 0 367.128-164.688 367.21-367.094.039-98.087-38.121-190.319-107.452-259.706zM523.544 808.047h-.125c-54.767-.021-108.483-14.729-155.344-42.529l-11.146-6.612-115.517 30.293 30.834-112.592-7.259-11.544c-30.552-48.579-46.688-104.729-46.664-162.379.066-168.229 136.985-305.096 305.339-305.096 81.521.031 158.154 31.811 215.779 89.482s89.342 134.332 89.312 215.859c-.066 168.243-136.984 305.118-305.209 305.118zm167.415-228.515c-9.177-4.591-54.286-26.782-62.697-29.843-8.41-3.062-14.526-4.592-20.645 4.592-6.115 9.182-23.699 29.843-29.053 35.964-5.352 6.122-10.704 6.888-19.879 2.296-9.176-4.591-38.74-14.277-73.786-45.526-27.275-24.319-45.691-54.359-51.043-63.543-5.352-9.183-.569-14.146 4.024-18.72 4.127-4.109 9.175-10.713 13.763-16.069 4.587-5.355 6.117-9.183 9.175-15.304 3.059-6.122 1.529-11.479-.765-16.07-2.293-4.591-20.644-49.739-28.29-68.104-7.447-17.886-15.013-15.466-20.645-15.747-5.346-.266-11.469-.322-17.585-.322s-16.057 2.295-24.467 11.478-32.113 31.374-32.113 76.521c0 45.147 32.877 88.764 37.465 94.885 4.588 6.122 64.699 98.771 156.741 138.502 21.892 9.45 38.982 15.094 52.308 19.322 21.98 6.979 41.982 5.995 57.793 3.634 17.628-2.633 54.284-22.189 61.932-43.615 7.646-21.427 7.646-39.791 5.352-43.617-2.294-3.826-8.41-6.122-17.585-10.714z" />
        </g>
        </svg></div>
								</div>
							</div>
							
							<script>
				( () => {
					const lazyloadRunObserver = () => {
						const lazyloadBackgrounds = document.querySelectorAll( `.e-con.e-parent:not(.e-lazyloaded)` );
						const lazyloadBackgroundObserver = new IntersectionObserver( ( entries ) => {
							entries.forEach( ( entry ) => {
								if ( entry.isIntersecting ) {
									let lazyloadBackground = entry.target;
									if( lazyloadBackground ) {
										lazyloadBackground.classList.add( 'e-lazyloaded' );
									}
									lazyloadBackgroundObserver.unobserve( entry.target );
								}
							});
						}, { rootMargin: '200px 0px 200px 0px' } );
						lazyloadBackgrounds.forEach( ( lazyloadBackground ) => {
							lazyloadBackgroundObserver.observe( lazyloadBackground );
						} );
					};
					const events = [
						'DOMContentLoaded',
						'elementor/lazyload/observe',
					];
					events.forEach( ( event ) => {
						document.addEventListener( event, lazyloadRunObserver );
					} );
				} )();
			</script>
			<script id="ht_ctc_app_js-js-extra">
var ht_ctc_chat_var = {"number":"61489987819","pre_filled":"How May I Help you...?","dis_m":"show","dis_d":"show","css":"cursor: pointer; z-index: 99999999;","pos_d":"position: fixed; bottom: 15px; right: 15px;","pos_m":"position: fixed; bottom: 15px; right: 15px;","side_d":"right","side_m":"right","schedule":"no","se":"150","ani":"no-animation","page_id":"1205","url_target_d":"_blank","ga":"yes","gtm":"1","fb":"yes","webhook_format":"json","g_init":"default","g_an_event_name":"click to chat","gtm_event_name":"Click to Chat","pixel_event_name":"Click to Chat by HoliThemes"};
var ht_ctc_variables = {"g_an_event_name":"click to chat","gtm_event_name":"Click to Chat","pixel_event_type":"trackCustom","pixel_event_name":"Click to Chat by HoliThemes","g_an_params_v4":[{"key":"number","value":"{number}"},{"key":"title","value":"{title}"},{"key":"url","value":"{url}"}],"g_an_params":["g_an_param_1","g_an_param_2","g_an_param_3"],"g_an_param_1":{"key":"number","value":"{number}"},"g_an_param_2":{"key":"title","value":"{title}"},"g_an_param_3":{"key":"url","value":"{url}"},"pixel_params_v4":[{"key":"Category","value":"Click to Chat for WhatsApp"},{"key":"ID","value":"{number}"},{"key":"Title","value":"{title}"},{"key":"URL","value":"{url}"}],"pixel_params":["pixel_param_1","pixel_param_2","pixel_param_3","pixel_param_4"],"pixel_param_1":{"key":"Category","value":"Click to Chat for WhatsApp"},"pixel_param_2":{"key":"ID","value":"{number}"},"pixel_param_3":{"key":"Title","value":"{title}"},"pixel_param_4":{"key":"URL","value":"{url}"},"gtm_params_v4":[{"key":"type","value":"chat"},{"key":"number","value":"{number}"},{"key":"title","value":"{title}"},{"key":"url","value":"{url}"},{"key":"ref","value":"dataLayer push"}],"gtm_params":["gtm_param_1","gtm_param_2","gtm_param_3","gtm_param_4","gtm_param_5"],"gtm_param_1":{"key":"type","value":"chat"},"gtm_param_2":{"key":"number","value":"{number}"},"gtm_param_3":{"key":"title","value":"{title}"},"gtm_param_4":{"key":"url","value":"{url}"},"gtm_param_5":{"key":"ref","value":"dataLayer push"}};
//# sourceURL=ht_ctc_app_js-js-extra
</script>
<script data-wp-strategy="defer" defer id="ht_ctc_app_js-js" src="./scripts/app.js"></script>
<script id="imagesloaded-js" src="./scripts/imagesloaded.min.js"></script>
<script id="oceanwp-main-js-extra">
var oceanwpLocalize = {"nonce":"724b71129a","isRTL":"","menuSearchStyle":"drop_down","mobileMenuSearchStyle":"disabled","sidrSource":null,"sidrDisplace":"1","sidrSide":"left","sidrDropdownTarget":"link","verticalHeaderTarget":"link","mobileDropdownTarget":"link","semanticMobileHeader":"1","semanticDesktopHeader":"1","customScrollOffset":"0","customSelects":".woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select","loadMoreLoadingText":"Loading...","ajax_url":"https://euroescortbangkok.com/wp-admin/admin-ajax.php","oe_mc_wpnonce":"b6fd3ccea1"};
//# sourceURL=oceanwp-main-js-extra
</script>
<script id="oceanwp-main-js" src="./scripts/theme.min.js"></script>
<script id="oceanwp-drop-down-mobile-menu-js" src="./scripts/drop-down-mobile-menu.min.js"></script>
<script id="oceanwp-drop-down-search-js" src="./scripts/drop-down-search.min.js"></script>
<script id="ow-magnific-popup-js" src="./scripts/magnific-popup.min.js"></script>
<script id="oceanwp-lightbox-js" src="./scripts/ow-lightbox.min.js"></script>
<script id="ow-flickity-js" src="./scripts/flickity.pkgd.min.js"></script>
<script id="oceanwp-slider-js" src="./scripts/ow-slider.min.js"></script>
<script id="oceanwp-scroll-effect-js" src="./scripts/scroll-effect.min.js"></script>
<script id="oceanwp-scroll-top-js" src="./scripts/scroll-top.min.js"></script>
<script id="oceanwp-select-js" src="./scripts/select.min.js"></script>
<script id="elementor-webpack-runtime-js" src="./scripts/webpack.runtime.min.js"></script>
<script id="elementor-frontend-modules-js" src="./scripts/frontend-modules.min.js"></script>
<script id="jquery-ui-core-js" src="./scripts/core.min.js"></script>
<script id="elementor-frontend-js-before">
var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false,"isScriptDebug":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnX":"Share on X","pinIt":"Pin it","download":"Download","downloadImage":"Download image","fullscreen":"Fullscreen","zoom":"Zoom","share":"Share","playVideo":"Play Video","previous":"Previous","next":"Next","close":"Close","a11yCarouselPrevSlideMessage":"Previous slide","a11yCarouselNextSlideMessage":"Next slide","a11yCarouselFirstSlideMessage":"This is the first slide","a11yCarouselLastSlideMessage":"This is the last slide","a11yCarouselPaginationBulletMessage":"Go to slide"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"responsive":{"breakpoints":{"mobile":{"label":"Mobile Portrait","value":767,"default_value":767,"direction":"max","is_enabled":true},"mobile_extra":{"label":"Mobile Landscape","value":880,"default_value":880,"direction":"max","is_enabled":false},"tablet":{"label":"Tablet Portrait","value":1024,"default_value":1024,"direction":"max","is_enabled":true},"tablet_extra":{"label":"Tablet Landscape","value":1200,"default_value":1200,"direction":"max","is_enabled":false},"laptop":{"label":"Laptop","value":1366,"default_value":1366,"direction":"max","is_enabled":false},"widescreen":{"label":"Widescreen","value":2400,"default_value":2400,"direction":"min","is_enabled":false}},"hasCustomBreakpoints":false},"version":"4.3.2","is_static":false,"experimentalFeatures":{"e_font_icon_svg":true,"additional_custom_breakpoints":true,"container":true,"nested-elements":true,"import-export-customization":true},"urls":{"assets":"https:\/\/euroescortbangkok.com\/wp-content\/plugins\/elementor\/assets\/","ajaxurl":"https:\/\/euroescortbangkok.com\/wp-admin\/admin-ajax.php","uploadUrl":"https:\/\/euroescortbangkok.com\/wp-content\/uploads"},"nonces":{"floatingButtonsClickTracking":"043e92b76f","atomicFormsSendForm":"0ce2d0f6db"},"swiperClass":"swiper","settings":{"page":[],"editorPreferences":[]},"kit":{"active_breakpoints":["viewport_mobile","viewport_tablet"],"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":1205,"title":"Tonya%20-%20Escort%20Website","excerpt":"","featuredImage":false}};
//# sourceURL=elementor-frontend-js-before
</script>
<script id="elementor-frontend-js" src="./scripts/frontend.min.js"></script><span id="elementor-device-mode" class="elementor-screen-only"></span>
<script id="swiper-js" src="./scripts/swiper.min.js"></script>
<script id="wp-emoji-settings" type="application/json">
{"baseUrl":"https://s.w.org/images/core/emoji/17.0.2/72x72/","ext":".png","svgUrl":"https://s.w.org/images/core/emoji/17.0.2/svg/","svgExt":".svg","source":{"concatemoji":"https://euroescortbangkok.com/wp-includes/js/wp-emoji-release.min.js?ver=7.0.6"}}
</script>
<script type="module">
/*! This file is auto-generated */
var e="script#wp-emoji-settings",t=document.querySelector(e);if(!(t instanceof HTMLScriptElement))throw new Error("Element missing: "+e);const r=JSON.parse(t.text),s=(window._wpemojiSettings=r,"wpEmojiSettingsSupports"),o=["flag","emoji"];function i(e){try{var t={supportTests:e,timestamp:(new Date).valueOf()};sessionStorage.setItem(s,JSON.stringify(t))}catch(e){}}function c(e,t,n){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);t=new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data);e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(n,0,0);const r=new Uint32Array(e.getImageData(0,0,e.canvas.width,e.canvas.height).data);return t.every((e,t)=>e===r[t])}function p(e,t){e.clearRect(0,0,e.canvas.width,e.canvas.height),e.fillText(t,0,0);var n=e.getImageData(16,16,1,1);for(let e=0;e<n.data.length;e++)if(0!==n.data[e])return!1;return!0}function u(e,t,n,r){switch(t){case"flag":return n(e,"\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f","\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f")?!1:!n(e,"\ud83c\udde8\ud83c\uddf6","\ud83c\udde8\u200b\ud83c\uddf6")&&!n(e,"\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f","\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f");case"emoji":return!r(e,"\ud83e\u1fac8")}return!1}function f(e,t,n,r){let a;const s=(a="undefined"!=typeof WorkerGlobalScope&&self instanceof WorkerGlobalScope?new OffscreenCanvas(300,150):document.createElement("canvas")).getContext("2d",{willReadFrequently:!0}),o=(s.textBaseline="top",s.font="600 32px Arial",{});return e.forEach(e=>{o[e]=t(s,e,n,r)}),o}function a(e){var t=document.createElement("script");t.src=e,t.defer=!0,document.head.appendChild(t)}r.supports={everything:!0,everythingExceptFlag:!0},new Promise(t=>{let n=function(){try{var e=JSON.parse(sessionStorage.getItem(s));if("object"==typeof e&&"number"==typeof e.timestamp&&(new Date).valueOf()<e.timestamp+604800&&"object"==typeof e.supportTests)return e.supportTests}catch(e){}return null}();if(!n){if("undefined"!=typeof Worker&&"undefined"!=typeof OffscreenCanvas&&"undefined"!=typeof URL&&URL.createObjectURL&&"undefined"!=typeof Blob)try{var e="postMessage("+f.toString()+"("+[JSON.stringify(o),u.toString(),c.toString(),p.toString()].join(",")+"));",r=new Blob([e],{type:"text/javascript"});const a=new Worker(URL.createObjectURL(r),{name:"wpTestEmojiSupports"});return void(a.onmessage=e=>{i(n=e.data),a.terminate(),t(n)})}catch(e){}i(n=f(o,u,c,p))}t(n)}).then(e=>{for(const n in e)r.supports[n]=e[n],r.supports.everything=r.supports.everything&&r.supports[n],"flag"!==n&&(r.supports.everythingExceptFlag=r.supports.everythingExceptFlag&&r.supports[n]);var t;r.supports.everythingExceptFlag=r.supports.everythingExceptFlag&&!r.supports.flag,r.supports.everything||((t=r.source||{}).concatemoji?a(t.concatemoji):t.wpemoji&&t.twemoji&&(a(t.twemoji),a(t.wpemoji)))});
//# sourceURL=https://euroescortbangkok.com/wp-includes/js/wp-emoji-loader.min.js
</script>
	

</body></html>