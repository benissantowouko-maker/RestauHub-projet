<?php
/**
 * Generate minimal colored PNG icons for the RestauHub PWA.
 * Run: php make_icons.php (from project root)
 */

function makeIcon(string $path, int $size, int $bgR, int $bgG, int $bgB): void
{
    $img = imagecreatetruecolor($size, $size);
    $bg  = imagecolorallocate($img, $bgR, $bgG, $bgB);
    imagefill($img, 0, 0, $bg);

    // White circle in center
    $fg = imagecolorallocate($img, 255, 255, 255);
    $cx = $cy = (int) ($size / 2);
    $r  = (int) ($size * 0.28);
    imagefilledellipse($img, $cx, $cy, $r * 2, $r * 2, $fg);

    imagepng($img, $path);
    imagedestroy($img);
    echo "Created: $path" . PHP_EOL;
}

$dir = __DIR__ . '/public/icons/';

// Client brand — deep red (#7F1D1D)
foreach ([72, 96, 128, 144, 152, 192, 384, 512] as $s) {
    makeIcon($dir . 'icon-' . $s . '.png', $s, 127, 29, 29);
}

// Staff brand — indigo (#4F46E5)
foreach ([192, 512] as $s) {
    makeIcon($dir . 'icon-staff-' . $s . '.png', $s, 79, 70, 229);
}

echo PHP_EOL . 'All icons generated successfully.' . PHP_EOL;
