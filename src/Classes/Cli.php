<?php

namespace MorningMedley\View\Classes;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class Cli
{

    private Container $app;
    private Filesystem $filesystem;

    public function __construct(Container $app, Filesystem $filesystem)
    {
        $this->app = $app;
        $this->filesystem = $filesystem;

        if (class_exists('\WP_CLI')) {
            \WP_CLI::add_command('medley make:view', [$this, 'makeView']);
        }
    }

    public function makeView(array $args, array $assocArray)
    {
        if (empty($args[0])) {
            \WP_CLI::error('Please supply a name for this view');
        }

        $name = trim($args[0]);
        $name = str_replace('.', DIRECTORY_SEPARATOR, $name);

        $path = Arr::first((array) $this->app['config']['view.paths']);
        $file = \trailingslashit($path) . $name . ".blade.php";

        $dir = pathinfo($file)['dirname'];

        if (! is_dir($dir)) {
            if (! mkdir($dir, 0777, true)) {
                \WP_Cli::error(\WP_Cli::colorize("Could not create directory: %b{$dir}%n"));
                exit;
            }
        }

        if (\file_put_contents($file, '', FILE_APPEND) === false) {
            \WP_Cli::error(\WP_Cli::colorize("Could not create view: %b{$file}%n"));
        }

        \WP_Cli::success(\WP_Cli::colorize("Created view %b{$file}%n"));
    }
}
