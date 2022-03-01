<?php

namespace App\View\Components;

use App\Models\Recording;
use Illuminate\View\Component;

class RecordingBreadcrumbs extends Component
{
    public function __construct(public Recording $recording)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.recording-breadcrumbs', [
            'parentsList' => $this->recording->computeParentsList(),
        ]);
    }
}
