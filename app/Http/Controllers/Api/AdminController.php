<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AdminUpdateRequest;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AdminRequest;
use App\Http\Requests\Api\AdminFilterRequest;
use App\Traits\ResponseTrait;

class AdminController extends Controller
{
    use ResponseTrait;

    public function index(AdminFilterRequest $request)
    {
        $result = Admin::filter($request)->with(["feature_image"])->get();
        return $this->responseSuccess($result, "Admin List");
    }


    public function store(AdminRequest $request)
    {
        $admin = Admin::create($request->safe()->toArray());
        return $this->responseSuccess(["id" => $admin->id], "Admin Created");
    }

    public function show(Admin $admin)
    {
        $admin->load(["feature_image"]);
        return $this->responseSuccess($admin, "Success");
    }

    public function update(AdminUpdateRequest $request, Admin $admin)
    {
        $data = $request->safe()->all();
        if (empty($data["password"])) {
            unset($data["password"]);
        }
        $admin->update($data);
        return $this->responseSuccess(["id" => $admin->id], "Admin Updated");
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return $this->responseSuccess([], "Admin Deleted");
    }
}
