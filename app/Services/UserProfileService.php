<?php

namespace App\Services;

use App\Http\Requests\UserProfileRequest;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileService
{
    function __construct(private $_user) {}

    public function update(UserProfileRequest $request)
    {
        $_user = Auth::user();
        $data = $request->safe()->all();
        if (!empty($data["password"])) {
            $data["password"] = Hash::make($data["password"]);
        }
        $_user->update($data);
        return true;
    }

    public function addAddress(UserProfileRequest $request)
    {
        $_user = Auth::user();
        $data = $request->safe()->all();
        $data["user_id"] = $_user->id;
        return UserAddress::create($data);
    }

    public function updateAddress(int $id, UserProfileRequest $request)
    {
        $_user = Auth::user();
        $data = $request->safe()->all();
        return UserAddress::where(["id" => $id, "user_id" => $_user->id])->update($data);
    }

    public function deleteAddress(int $id)
    {
        $_user = Auth::user();
        return UserAddress::where(["id" => $id, "user_id" => $_user->id])->delete();
    }
}
