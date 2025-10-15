<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LocaleSwitch extends Component
{
    public string $locale;

    public function __construct()
    {
        $this->locale = app()->getLocale();
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Support\Htmlable|string|\Closure|\Illuminate\View\View
    {
        return view('components.locale-switch');
    }
}
