<?php
// Cacheable, dependency-free fallback for product image files that are not
// present on an ephemeral deployment filesystem.
$image = isset($_GET['image']) ? basename((string) $_GET['image']) : 'product.jpg';
$name = isset($_GET['name']) ? trim((string) $_GET['name']) : '';
$category = strtoupper(preg_replace('/[^a-z0-9]+/i', ' ', preg_replace('/_(?:p|mock).*$/i', '', pathinfo($image, PATHINFO_FILENAME))));
if ($category === '' || $category === 'PRODUCT') {
    $category = 'H-MART';
}
$label = $name !== '' ? $name : $category . ' PRODUCT';
$label = function_exists('mb_substr') ? mb_substr($label, 0, 34) : substr($label, 0, 34);

$palettes = [
    ['#0f766e', '#5eead4'], ['#1d4ed8', '#93c5fd'], ['#7c3aed', '#c4b5fd'],
    ['#be123c', '#fda4af'], ['#b45309', '#fcd34d'], ['#047857', '#6ee7b7'],
];
$palette = $palettes[abs(crc32($category)) % count($palettes)];

header('Content-Type: image/svg+xml; charset=UTF-8');
header('Cache-Control: public, max-age=86400, stale-while-revalidate=604800');
header('X-Content-Type-Options: nosniff');

$categoryEsc = htmlspecialchars($category, ENT_QUOTES | ENT_XML1, 'UTF-8');
$labelEsc = htmlspecialchars($label, ENT_QUOTES | ENT_XML1, 'UTF-8');
?>
<svg xmlns="http://www.w3.org/2000/svg" width="700" height="700" viewBox="0 0 700 700" role="img" aria-label="<?= $labelEsc ?>">
  <defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop stop-color="<?= $palette[0] ?>"/><stop offset="1" stop-color="<?= $palette[1] ?>"/></linearGradient></defs>
  <rect width="700" height="700" rx="40" fill="url(#g)"/>
  <circle cx="350" cy="285" r="150" fill="#fff" opacity=".18"/>
  <path d="M255 270h190l-20 170H275zM300 275c0-75 100-75 100 0" fill="none" stroke="#fff" stroke-width="24" stroke-linejoin="round" stroke-linecap="round"/>
  <text x="350" y="520" fill="#fff" text-anchor="middle" font-family="Arial,sans-serif" font-size="34" font-weight="700"><?= $categoryEsc ?></text>
  <text x="350" y="570" fill="#fff" opacity=".9" text-anchor="middle" font-family="Arial,sans-serif" font-size="22"><?= $labelEsc ?></text>
</svg>
