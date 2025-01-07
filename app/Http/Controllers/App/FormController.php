<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\ContactUsRequest;
use App\Http\Requests\App\SubscribeRequest;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Contactus;
use App\Models\Subscriber;
use App\Traits\ResponseTrait;

class FormController extends Controller
{
    use ResponseTrait;

    public function contactus(ContactUsRequest $request)
    {
        Contactus::create($request->safe()->all());
        return $this->responseSuccess([], "Request saved");
    }

    public function subscribe(SubscribeRequest $request)
    {
        Subscriber::create($request->safe()->all());
        return $this->responseSuccess([], "Request saved");
    }
}
