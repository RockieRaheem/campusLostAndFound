<?php

namespace App\Jobs;

use App\Models\ItemPhoto;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessItemPhoto implements ShouldQueue
{
    use Queueable;

    public function __construct(public ItemPhoto $photo)
    {
    }

    public function handle(): void
    {
        if (empty($this->photo->path)) {
            return;
        }

        $fullPath = Storage::disk('public')->path($this->photo->path);

        if (file_exists($fullPath)) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);
            
            $image->scaleDown(1200, 1200);
            $encoded = $image->toWebp(80);

            $newFilename = 'item-photos/' . uniqid() . '.webp';
            
            Storage::disk('public')->put($newFilename, (string) $encoded);
            Storage::disk('public')->delete($this->photo->path);

            $this->photo->update(['path' => $newFilename]);
        }
    }
}
