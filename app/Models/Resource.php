<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Resource extends Model
{
    use HasFactory;

    public function isEmpty(): string
    {
        return $this->name === null;
    }

    public function isImage()
    {
        if (str_starts_with($this->type, 'image/')) {
            return true;
        }
        return false;
    }

    public function getPath(): string
    {
        if ($this->name == null) {
            return asset('assets/images/no_image_available.svg');
        }
        $parts = explode("\\", $this->imageable_type);
        return asset('uploads/' . strtolower(array_pop($parts)) . '/' . $this->name);
    }

    public function copyCat()
    {
        $parts = explode("\\", $this->imageable_type);
        $path = 'uploads/' . strtolower(array_pop($parts)) . '/';
        copy($path . $this->name, $path . 'seo_' . $this->name);
        return $path . 'seo_' . $this->name;
    }

    public function getRawPath(): string
    {
        if ($this->name == null) {
            return asset('assets/images/no_image_available.svg');
        }
        $parts = explode("\\", $this->imageable_type);
        return 'uploads/' . strtolower(array_pop($parts)) . '/' . $this->name;
    }
}
