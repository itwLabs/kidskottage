<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactUsFilterRequest;
use App\Models\Contactus;
use App\Traits\ResponseTrait;

class ContactUsController extends Controller
{
    use ResponseTrait;

    public function index(ContactUsFilterRequest $request)
    {
        $result = Contactus::filter($request)->get();
        return $this->responseSuccess($result, "Contact List");
    }

    public function show(Contactus $contactus)
    {
        $contactus->update(["read" => 1]);
        $contactus->refresh();
        return $this->responseSuccess($contactus, "Success");
    }

    public function update(Contactus $contactus)
    {
        $contactus->delete();
        return $this->responseSuccess(["id" => $contactus->id], "Contact Updated");
    }
}
