<?php

namespace App\Services;

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Listing;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Slider;

class HomeService
{

    public function sliders()
    {
        return Slider::with(["feature_image"])
            ->get();
    }

    public function featureCategories()
    {
        return Category::with(["feature_image"])->with(["childs.childs"])
            ->where("feature_no", "!=", 0)
            ->orderBy("feature_no", "ASC")
            ->limit(20)
            ->get();
    }

    public function trandingCategories()
    {
        return Category::with(["feature_image"])
            ->where(["on_trending" => 1])
            ->limit(20)
            ->get();
    }

    public function listing()
    {
        return Listing::with(["feature_image"])
            ->limit(20)
            ->get();
    }

    public function agegroup()
    {
        return AgeGroup::with(["feature_image"])
            ->get();
    }

    public function ads()
    {
        return Section::with(["feature_image"])
            ->typeAds()
            ->get();
    }

    public function gender()
    {
        return Section::with(["feature_image"])
            ->typeGender()
            ->get();
    }

    public function newsletter()
    {
        return Section::with(["feature_image"])
            ->typeLetter()
            ->get();
    }

    public function services()
    {
        return Section::with(["feature_image"])
            ->typeService()
            ->get();
    }

    public function faqs()
    {
        return Faq::with(["feature_image"])
            ->get();
    }

    public function siteinfo()
    {
        return Setting::first();
    }
}
