<?php

namespace Waterhole\Views\Components;

use Illuminate\View\Component;

class Layout extends Component
{
    public ?string $title;

    public function __construct(string $title = null)
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('waterhole::components.layout');
    }
}