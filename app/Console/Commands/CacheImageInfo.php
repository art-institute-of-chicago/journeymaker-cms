<?php

namespace App\Console\Commands;

use App\Libraries\DamsImageService;
use App\Models\Artwork;
use Illuminate\Console\Command;

class CacheImageInfo extends Command
{
    protected $signature = 'app:cache-image-info';

    protected $description = 'Caches image info required for building JSON output.';

    public function __construct(
        private readonly DamsImageService $damsImageService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->withProgressBar(Artwork::active()->get(), function (Artwork $artwork) {
            $this->damsImageService->getImage($artwork);
        });
    }
}
