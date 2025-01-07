<?php

namespace App\Observers;

use App\Models\Page;

class PageObserver
{
    public function creating(Page $page): void
    {
        if (is_null($page->order)) {
            $page->order = Page::max('order') + 1;
            return;
        }
    }

    public function deleted(Page $page): void
    {
        $lowerPriorityPages = Page::select(['id', 'order'])->where('order', '>', $page->order)->get();

        foreach ($lowerPriorityPages as $lowerPriorityPage) {
            $lowerPriorityPage->order--;
            $lowerPriorityPage->saveQuietly();
        }
    }
}
