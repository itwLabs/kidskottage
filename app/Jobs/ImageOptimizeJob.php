<?php

namespace App\Jobs;

use App\Models\Model;
use App\Models\Resource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;

class ImageOptimizeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $resource, $host;
    public function __construct(Resource $resource, Model $host)
    {
        $this->resource = $resource;
        $this->host = $host;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->resource->isEmpty() || $this->resource->type !== "image") {
            return;
        }

        $image = Image::make(public_path($this->resource->getRawPath()));
        if (!in_array($this->resource->repo_type, $this->host->no_optimize)) {
            $image->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            // $image->resize(1920, null, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // });
        }
        $image->save(public_path($this->resource->getRawPath()));
    }
}
