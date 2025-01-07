<?php

namespace App\Traits;

use App\Jobs\ImageOptimizeJob;
use App\Models\Resource;

trait ResourceTrait
{
    private $_resource = ["feature_image"];
    private $_no_optimize = [];
    private $multi_resource = [];
    public $_resource_id = [];

    public static function bootResourceTrait()
    {
        static::creating(function ($model) {
            $model->processResource();
        });

        static::created(function ($model) {
            $model->updateResource();
        });

        static::updating(function ($model) {
            $model->processResource();
        });

        static::updated(function ($model) {
            $model->updateResource();
        });
    }

    public function getFirstResource()
    {
        $resource = "_resource";
        if (property_exists($this, 'resource')) {
            $resource = "resource";
        }
        if (count($this->$resource) == 0) {
            return null;
        }
        $firstResource = $this->$resource[0];
        return $this->$firstResource;
    }

    // Magic method to get resource's relational model by its name in `resource` and `resources` variable in camelCase
    public function __call($method, $parameters)
    {
        $resource = "_resource";
        if (property_exists($this, 'resource')) {
            $resource = "resource";
        }
        // Check if the called method matches any of the camelCase resource
        if (in_array($method, $this->$resource)) {
            // Assuming you have a method to define the relation
            return $this->getResourceRelation($method);
        }

        // Call the parent __call method to handle other dynamic methods
        return parent::__call($method, $parameters);
    }
    // Function to extract single relational resource
    private function getResourceRelation($resource)
    {
        return $this->morphOne(Resource::class, 'resoable')->where(["reso_type" => $resource])
            ->orderBy("order", "ASC")
            ->orderBy("id", "DESC")
            ->withDefault([
                'file_name' => null,
                'alt' => "File not found",
            ]);
    }

    public function processResource()
    {
        $resource = "_resource";
        if (property_exists($this, 'resource')) {
            $resource = "resource";
        }
        foreach ($this->$resource as $res) {
            if (isset($this->attributes[$res])) {
                $this->_resource_id[$res] = $this->attributes[$res];
                // Clean the resource related attribute
                unset($this->attributes[$res]);
            }
        }
    }

    public function updateResource()
    {
        foreach ($this->_resource_id as $key => $res) {
            $resource = Resource::where(["id" => $res, "moved" => 0])->first();
            if ($resource == null) {
                continue;
            }
            if (!in_array($key, $this->multi_resource)) {
                Resource::where("id", "!=", $resource->id)->where([
                    "reso_type" => $key,
                    "resoable_type" => static::class,
                    "resoable_id" => $this->id
                ])->delete();
            }
            $resource->update([
                "resoable_type" => static::class,
                "resoable_id" => $this->id,
                "reso_type" => $key,
                "moved" => 1
            ]);
            // Perform image optimization
            dispatch(new ImageOptimizeJob($resource, $this));
        }
    }
}
