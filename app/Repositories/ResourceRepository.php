<?php

namespace App\Repositories;

use App\Models\Resource;
use Exception;
use Illuminate\Http\UploadedFile as File;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class ResourceRepository
{
    function uploadImage(File $file, string $alt_text = null)
    {
        if ($alt_text == null) {
            $alt_text = $this->getFileAlt($file);
        }
        $folder_name = $this->getFolderName();
        $slug = Str::slug($alt_text);
        $filename = $slug . "-" . rand(100000, 999999) . "." . $file->getClientOriginalExtension();
        // Log::info($file);
        $file->storeAs($folder_name, $filename, ['disk' => 'public_uploads']);
        // $file->move($folder_name, $filename);
        $resource = Resource::create([
            "alt" => $alt_text,
            "file_name" => $filename,
            "name" => $file->getClientOriginalName(),
            "type" => $file->getMimeType(),
            "size" => $file->getSize(),
            "url" => config("app.url") . $folder_name . "/" . $filename
        ]);
        return $resource;
    }

    public function getById(int $id): ?Resource
    {
        $resource = Resource::find($id);
        if (empty($resource)) {
            return throw new Exception("Product does not exist.", Response::HTTP_NOT_FOUND);
        }
        return $resource;
    }

    public function update($data, int $id)
    {
        $resource = $this->getById($id);
        $data = $this->prepareForDB($data);
        if (count($data)) {
            $resource->update($data);
            $resource->refresh();
        }
        return $resource;
    }

    public function delete(int $id)
    {
        $resource = $this->getById($id);
        $resource->delete();
        return true;
    }

    public function prepareForDB(array $data): array
    {

        $newData = [];
        if (isset($data["alt"])) {
            $newData["alt"] = $data["alt"];
        }
        if (isset($data["order"])) {
            $newData["order"] = $data["order"];
        }

        return $newData;
    }

    private function getFileAlt($file)
    {
        return ($file->getClientOriginalName());
    }

    private function getFolderName()
    {
        $folder = 'uploads/resource';
        $path = public_path($folder);
        if (!file_exists($path)) {
            mkdir($path);
        }
        return $folder;
    }
}
