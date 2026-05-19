<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GeneratePwaIcons extends Command
{
    protected $signature = 'pwa:icons {--source=images/Hapklaar-LOGO.png : Source image relative to public/}';
    protected $description = 'Generate PWA icon PNG files from source image';

    public function handle(): int
    {
        $source = public_path($this->option('source'));

        if (!file_exists($source)) {
            $this->error("Source not found: {$source}");
            return 1;
        }

        $outputDir = public_path('icons');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $manager = new ImageManager(new Driver());
        $sizes = [192, 512];

        foreach ($sizes as $size) {
            $dest = "{$outputDir}/icon-{$size}.png";
            $manager->decode($source)
                ->cover($size, $size)
                ->save($dest);
            $this->info("Generated: public/icons/icon-{$size}.png");
        }

        $this->info('Done. Run `php artisan pwa:icons` after updating the logo.');
        return 0;
    }
}
