<?php

namespace MorningMedley\View\Classes;

use Illuminate\View\Compilers\BladeCompiler;

class Directives
{
    public function registerDirectives(BladeCompiler $compiler)
    {
        $compiler->directive('enqueueScript', [$this, 'script']);
        $compiler->directive('enqueueStyle', [$this, 'style']);
        $compiler->directive('shortcode', [$this, 'shortcode']);
        $compiler->directive('cache', [$this, 'cache']);
        $compiler->directive('endcache', [$this, 'endcache']);
        $compiler->if('auth', [$this, 'wpauth']);
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

    /**
     * Test if a user is logged ind or not and optionally if the user has a specific capability
     *
     * @auth
     * @auth(string $capability)
     *
     * @see https://developer.wordpress.org/reference/functions/current_user_can/
     * @param  string|null  $expression
     * @return bool
     */
    public function wpauth(?string $expression = null): bool
    {
        return $expression === null ? \is_user_logged_in() : \current_user_can($expression);
    }

    /**
     * @cache(string $transientName, int $expiration = DAY_IN_SECONDS)
     *
     * @param  string|null  $expression
     * @return string
     */
    public function cache(string $expression = null)
    {
        return "<?php
            \$arguments = [{$expression}];
            if(count(\$arguments) === 2){
                [\$transientName, \$expiration] = \$arguments;
            }else{
                [\$transientName] = \$arguments;
                \$expiration = DAY_IN_SECONDS;
            }
            \$cachedContent = \get_transient(\$transientName);
            if(\$cachedContent !== false){
                echo \$cachedContent;
            }else{
                \$isCaching = true;
                ob_start();
        ?>";
    }

    public function endcache()
    {
        return "<?php
                \$content = ob_get_clean();
                \set_transient(\$transientName, \$content, \$expiration);
            
                echo \$content;
                unset(\$isCaching, \$content, \$transientName, \$expiration);
            }
        ?>";
    }
}
