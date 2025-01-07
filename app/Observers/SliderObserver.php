<?php

namespace App\Observers;

use App\Models\Slider;

class SliderObserver
{
    public function creating(Slider $slider): void
    {
        if (is_null($slider->order)) {
            $slider->order = Slider::max('order') + 1;
            return;
        }
    }

    public function deleted(Slider $slider): void
    {
        $lowerPrioritySliders = Slider::select(['id', 'order'])->where('order', '>', $slider->order)->get();

        foreach ($lowerPrioritySliders as $lowerPrioritySlider) {
            $lowerPrioritySlider->order--;
            $lowerPrioritySlider->saveQuietly();
        }
    }
}
