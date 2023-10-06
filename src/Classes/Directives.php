<?php

namespace MorningMedley\View\Classes;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\View;

class Directives
{
    public function registerDirectives()
    {
        Blade::directive('enqueueScript', [$this, 'script']);
        Blade::directive('enqueueStyle', [$this, 'style']);
        Blade::directive('shortcode', [$this, 'shortcode']);

        Blade::if('auth', [$this, 'wpauth']);

    }

    /**
     * @ script(string $handle)
     * Calls wp_enqueue_script with the given handle
     *
     * @param  string  $expression
     * @return string
     */
    public function script(string $expression): string
    {
        return "<?php \wp_enqueue_script({$expression}); ?>";
    }

    /**
     * @ style(string $handle)
     * Calls wp_enqueue_style with the given handle
     *
     * @param  string  $expression
     * @return string
     */
    public function style(string $expression): string
    {
        return "<?php \wp_enqueue_style({$expression}); ?>";
    }

    /**
     * @ shortcode(string $shortcode, bool $ignore_html = false)
     * echos do_shortcode
     *
     * @see https://developer.wordpress.org/reference/functions/do_shortcode/
     *
     * @param  string  $expression
     * @return string
     */
    public function shortcode(string $expression): string
    {
        return "<?php echo \do_shortcode({$expression}); ?>";
    }

    public function wpauth(?string $expression = null): bool
    {
        return $expression === null ? \is_user_logged_in() : \current_user_can($expression);
    }
}
