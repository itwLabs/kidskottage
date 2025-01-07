<?php

namespace App\Traits;

use App\Models\Seo;
use App\Models\Model;
use App\Models\Resource;

trait SeoTrait
{

    public static function bootSeoTrait()
    {
        static::created(function (Model $model) {

            $seo = Seo::create([
                "name" => $model->{$model->seo_name ?? "name"},
                "meta" => $model->{$model->seo_meta ?? "name"},
                "description" => $model->{$model->seo_description ?? "name"},
                "seoable_type" => static::class,
                "seoable_id" => $model->id
            ]);
            $resource = $model->getFirstResource();

            if ($resource != null) {
                $file_name = $resource->copyCat();
                Resource::create([
                    "alt" => $resource->alt,
                    "name" => 'seo_' . $resource->name,
                    "type" => $resource->type,
                    "size" => $resource->alt,
                    "reso_type" => "seo_image",
                    "resoable_type" => Seo::class,
                    "resoable_id" => $seo->id,
                    "url" => config("app.url") . $file_name
                ]);
            }
        });
    }

    public function updateSeo($seo): Seo
    {
        $oldseo = $this->seo;
        if ($oldseo->id == null) {
            $oldseo = $this->seo()->create($seo);
        } else {
            $oldseo->update($seo);
        }
        return $oldseo;
    }


    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable')->withDefault([
            'name' => config("app.name"),
            'meta' => config("app.name"),
            'description' => config("app.name")
        ]);
    }
}
