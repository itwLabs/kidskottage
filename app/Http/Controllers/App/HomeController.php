<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use App\Traits\ResponseTrait;

class HomeController extends Controller
{
    use ResponseTrait;

    public function __construct(private HomeService $homeService) {}

    public function index()
    {
        return $this->responseSuccess([
            "slider" => $this->homeService->sliders(),
            "featureCategories" => $this->homeService->featureCategories(),
            "trandingCategories" => $this->homeService->trandingCategories(),
            // "listing" => $this->homeService->listing(),
            "agegroup" => $this->homeService->agegroup(),
            "ads" => $this->homeService->ads(),
            "gender" => $this->homeService->gender(),
            "newsletter" => $this->homeService->newsletter(),
            "services" => $this->homeService->services(),
        ]);
    }

    public function slider()
    {
        return $this->responseSuccess([
            "slider" => $this->homeService->sliders(),
        ]);
    }

    public function featureCategories()
    {
        return $this->responseSuccess([
            "featureCategories" => $this->homeService->featureCategories(),
        ]);
    }

    public function trandingCategories()
    {
        return $this->responseSuccess([
            "trandingCategories" => $this->homeService->trandingCategories(),
        ]);
    }

    public function listing()
    {
        return $this->responseSuccess([
            "listing" => $this->homeService->listing(),
            "agegroup" => $this->homeService->agegroup(),
            "ads" => $this->homeService->ads(),
            "gender" => $this->homeService->gender(),
            "newsletter" => $this->homeService->newsletter(),
            "services" => $this->homeService->services(),
        ]);
    }

    public function agegroup()
    {
        return $this->responseSuccess([
            "agegroup" => $this->homeService->agegroup(),
        ]);
    }

    public function ads()
    {
        return $this->responseSuccess([
            "ads" => $this->homeService->ads(),
        ]);
    }

    public function gender()
    {
        return $this->responseSuccess([
            "gender" => $this->homeService->gender(),
            "newsletter" => $this->homeService->newsletter(),
            "services" => $this->homeService->services(),
        ]);
    }

    public function newsletter()
    {
        return $this->responseSuccess([
            "newsletter" => $this->homeService->newsletter(),
            "services" => $this->homeService->services(),
        ]);
    }

    public function services()
    {
        return $this->responseSuccess([
            "services" => $this->homeService->services()
        ]);
    }

    public function faqs()
    {
        return $this->responseSuccess([
            "faqs" => $this->homeService->faqs()
        ]);
    }

    public function siteinfo()
    {
        return $this->responseSuccess([
            "siteinfo" => $this->homeService->siteinfo()
        ]);
    }
}
