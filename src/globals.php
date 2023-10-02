<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

if (! function_exists('view')) {
    function view(
        string $view,
        Arrayable|array $data = [],
        array $mergeData = []
    ): View {
        return ViewFacade::make($view, $data, $mergeData);
    }
}
