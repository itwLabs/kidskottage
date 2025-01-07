<?php

namespace App\Observers;

use App\Models\Faq;

class FaqObserver
{
    public function creating(Faq $faq): void
    {
        if (is_null($faq->order)) {
            $faq->order = Faq::max('order') + 1;
            return;
        }
    }

    public function deleted(Faq $faq): void
    {
        $lowerPriorityFaqs = Faq::select(['id', 'order'])->where('order', '>', $faq->order)->get();

        foreach ($lowerPriorityFaqs as $lowerPriorityFaq) {
            $lowerPriorityFaq->order--;
            $lowerPriorityFaq->saveQuietly();
        }
    }
}
