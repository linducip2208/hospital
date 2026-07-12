<?php
/**
 * Generate PWA icons (PNG) menggunakan PHP GD.
 * Run: php scripts/generate-icons.php
 */

$sizes = [
    ['size' => 192, 'file' => __DIR__ . '/../public/icons/icon-192.png', 'maskable' => false],
    ['size' => 512, 'file' => __DIR__ . '/../public/icons/icon-512.png', 'maskable' => false],
    ['size' => 512, 'file' => __DIR__ . '/../public/icons/icon-maskable-512.png', 'maskable' => true],
    ['size' => 180, 'file' => __DIR__ . '/../public/icons/apple-touch-icon.png', 'maskable' => false],
    ['size' => 32,  'file' => __DIR__ . '/../public/icons/favicon-32.png', 'maskable' => false],
];

foreach ($sizes as $cfg) {
    $size = $cfg['size'];
    $maskable = $cfg['maskable'];

    $im = imagecreatetruecolor($size, $size);
    imagesavealpha($im, true);
    imagealphablending($im, false);

    $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $transparent);
    imagealphablending($im, true);

    $blue1 = imagecolorallocate($im, 0x25, 0x63, 0xeb);
    $blue2 = imagecolorallocate($im, 0x06, 0xb6, 0xd4);
    $white = imagecolorallocate($im, 0xff, 0xff, 0xff);

    // Background gradient (vertical interpolation)
    for ($y = 0; $y < $size; $y++) {
        $t = $y / $size;
        $r = (int) (0x25 + ($t * (0x06 - 0x25)));
        $g = (int) (0x63 + ($t * (0xb6 - 0x63)));
        $b = (int) (0xeb + ($t * (0xd4 - 0xeb)));
        $color = imagecolorallocate($im, $r, $g, $b);
        imageline($im, 0, $y, $size, $y, $color);
    }

    // Rounded corners (mask) — non-maskable saja
    if (! $maskable) {
        $radius = (int) ($size * 0.18);
        roundCorners($im, $size, $radius);
    }

    // Plus cross icon (medical) — maskable lebih kecil supaya safe area
    $padding = $maskable ? 0.32 : 0.22;
    $thickness = (int) ($size * 0.18);
    $cx = (int) ($size / 2);
    $cy = (int) ($size / 2);
    $arm = (int) ($size * (1 - 2 * $padding) / 2);

    // Horizontal bar
    imagefilledrectangle(
        $im,
        $cx - $arm,
        $cy - (int)($thickness / 2),
        $cx + $arm,
        $cy + (int)($thickness / 2),
        $white
    );
    // Vertical bar
    imagefilledrectangle(
        $im,
        $cx - (int)($thickness / 2),
        $cy - $arm,
        $cx + (int)($thickness / 2),
        $cy + $arm,
        $white
    );

    imagepng($im, $cfg['file'], 9);
    imagedestroy($im);
    echo "Generated: {$cfg['file']} ({$size}x{$size})\n";
}

function roundCorners($im, int $size, int $radius): void
{
    $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagealphablending($im, false);

    for ($x = 0; $x < $radius; $x++) {
        for ($y = 0; $y < $radius; $y++) {
            $dx = $radius - $x;
            $dy = $radius - $y;
            if (($dx * $dx + $dy * $dy) > ($radius * $radius)) {
                imagesetpixel($im, $x, $y, $transparent); // top-left
                imagesetpixel($im, $size - 1 - $x, $y, $transparent); // top-right
                imagesetpixel($im, $x, $size - 1 - $y, $transparent); // bottom-left
                imagesetpixel($im, $size - 1 - $x, $size - 1 - $y, $transparent); // bottom-right
            }
        }
    }

    imagealphablending($im, true);
}

echo "\nDone. Icons saved to public/icons/\n";
