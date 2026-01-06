<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CandidatesList extends Component
{
    public LengthAwarePaginator $candidates;

    /**
     * Create a new component instance.
     */

    public function __construct(LengthAwarePaginator $candidates)
    {
        $this->candidates = $candidates;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.candidates-list');
    }
}
