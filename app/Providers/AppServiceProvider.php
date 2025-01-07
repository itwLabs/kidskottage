<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Slider;
use App\Models\User;
use App\Observers\BrandObserver;
use App\Observers\FaqObserver;
use App\Observers\PageObserver;
use App\Observers\SliderObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Page::observe(PageObserver::class);
        // Brand::observe(BrandObserver::class);
        // Faq::observe(FaqObserver::class);
        // Slider::observe(SliderObserver::class);
        // User::observe(UserObserver::class);
    }
}
