<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\PasswordUpdateRequest;
use App\Http\Requests\App\ProfileAddressRequest;
use App\Http\Requests\App\ProfileUpdateRequest;
use App\Models\User;
use App\Models\UserAddress;
use App\Repositories\AuthRepository;
use App\Services\UserProfileService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    use ResponseTrait;
    private UserProfileService $_profileService;
    private AuthRepository $auth;

    function initialService()
    {
        $this->auth = new AuthRepository();
        $user = Auth::guard()->user();
        $this->_profileService = new UserProfileService($user);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"Authentication"},
     *     summary="User profile",
     *     description="User profile",
     *     operationId="profile",
     *     security={{"bearer":{}}},
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
    public function show(): JsonResponse
    {
        try {
            $user = Auth::guard()->user();
            $user->load(["feature_image"]);
            return $this->responseSuccess($user, 'User profile data.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        try {
            $user = User::where(["id" => Auth::id()])->first();
            $data = $request->safe()->all();
            return $this->responseSuccess($user->update($data), 'User profile update success.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    public function addressList(): JsonResponse
    {
        try {
            $user = Auth::guard()->user();
            $address = $user->addresses()->get();
            return $this->responseSuccess($address, 'User profile data.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    public function addressUpdate($id, ProfileAddressRequest $request): JsonResponse
    {
        try {
            $user = Auth::guard()->user();
            $address = UserAddress::findOrFail([
                "user_id" => $user->id,
                "id" => $id
            ]);
            $data = $request->safe()->all();
            $address->update($data);
            return $this->responseSuccess($address->update($data), 'User address update success.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    public function addressAdd(ProfileAddressRequest $request): JsonResponse
    {
        try {
            $data = $request->safe()->all();
            $user = Auth::guard()->user();
            $data["user_id"] = $user->id;
            $address = UserAddress::create($data);
            return $this->responseSuccess($address, 'User address added success.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    public function addressDelete($id): JsonResponse
    {
        try {
            $user = Auth::guard()->user();
            UserAddress::where([
                "user_id" => $user->id,
                "id" => $id
            ])->delete();
            return $this->responseSuccess([], 'User address delete success.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::findOrFail(auth()->user()->id);
            if (Hash::check($data['old_password'], $user->password)) {
                $hash = Hash::make($data['password']);
                $user->update(['password' => $hash]);
                return $this->responseSuccess([], "Password updated successfully!");
            } else {
                return $this->responseError([], "You added wrong existing password!");
            }
        } catch (\Throwable $th) {
            return $this->responseError([], $th->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     description="User logout",
     *     operationId="logout",
     *     security={{"bearer":{}}},
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
    public function logout(): JsonResponse
    {
        try {
            Auth::guard()->user()->token()->revoke();
            Auth::guard()->user()->token()->delete();
            return $this->responseSuccess([], 'User logged out successfully.');
        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
