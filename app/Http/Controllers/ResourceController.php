<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Requests\FileUpdateRequest;
use App\Models\Resource;
use App\Repositories\ResourceRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResourceController extends Controller
{
    use ResponseTrait;

    public function __construct(private ResourceRepository $reso)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/resource/upload",
     *     tags={"Resource"},
     *     summary="Resource",
     *     description="this endpoint accept files such as pdf, image, excel, and other",
     *     operationId="resource",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="file",
     *                     description="File to upload",
     *                     type="file"
     *                 ),
     *                 @OA\Property(
     *                     property="alt",
     *                     description="Alt text for image which helps to image seo",
     *                     type="string",
     *                     example="Brand nick image"
     *                 ),
     *                 required = {"file"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function uploadFile(FileRequest $reso): JsonResponse
    {
        try {
            $repo = $this->reso->uploadImage($reso->file("file"), $reso->get("alt"));
            return $this->responseSuccess($repo, 'File Uploaded.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }


    public function update(FileUpdateRequest $reso, int $id): JsonResponse
    {
        try {
            $repo = $this->reso->update($reso->safe()->toArray(), $id);
            return $this->responseSuccess($repo, 'File Updated');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $repo = $this->reso->delete($id);
            return $this->responseSuccess($repo, 'File Updated');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
