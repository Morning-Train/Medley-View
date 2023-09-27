<?php

use Illuminate\Contracts\Support\Arrayable;

if (! function_exists('view')) {
    function view(
        string $view,
        Arrayable|array $data = [],
        array $mergeData = []
    ): string {
        return \Illuminate\Support\Facades\View::make($view, $data, $mergeData)->render();
    }
}
