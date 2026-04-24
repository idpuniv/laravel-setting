<?php

namespace Idpuniv\Setting\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'setting:install';
    protected $description = 'Install the Setting package';

    public function handle()
    {
        $this->info('Starting Setting package installation...');

        // Publish application files (app directory)
        $this->publishApp();

        // Publish routes (web_base.php)
        $this->publishRoutes();

        // Publish migrations (database migrations)
        $this->publishMigrations();

        // Publish config
        $this->info('Publication de la config...');
        $this->call('vendor:publish', [
            '--tag' => 'settings-config',
        ]);
        $this->info('Installation complete.');
    }

    /**
     * Publish application files (app directory)
     */
    private function publishApp()
    {
        $source = __DIR__ . '/../../'; // Source directory
        $destination = app_path(); // Destination application directory

        $files = File::allFiles($source);

        // Files to exclude from publishing
        $exclude = [
            'Providers/SettingServiceProvider.php',
            'Console/Commands/InstallCommand.php',
        ];

        foreach ($files as $file) {

            $relativePath = $file->getRelativePathname();

            // Skip excluded files
            if (in_array($relativePath, $exclude)) {
                continue;
            }

            $content = $file->getContents();

            // Correct namespace for app files
            $content = str_replace(
                'namespace Idpuniv\\Setting\\',
                'namespace App\\',
                $content
            );

            $content = str_replace(
                'Idpuniv\\Setting\\',
                'App\\',
                $content
            );

            $target = $destination . '/' . $relativePath;

            // Ensure the directory exists before writing the file
            File::ensureDirectoryExists(dirname($target));
            File::put($target, $content);
        }

        $this->info('App files published (with exclusions).');
    }

    /**
     * Publish database seeders
     */
    private function publishSeeders()
    {
        $source = __DIR__ . '/../../../database/seeders'; // Source seeders directory
        $destination = database_path('seeders'); // Destination seeders directory

        // Copy the seeders directory to the application
        File::copyDirectory($source, $destination);

        $this->info('Seeders published.');
    }

    /**
     * Publish routes (web.php)
     */
    private function publishRoutes()
    {
        $source = __DIR__ . '/../../../routes/web.php'; // Source routes file
        $destination = base_path('routes/web_setting.php'); // Destination routes file

        // Copy the web.php file from the package to the application
        File::copy($source, $destination);

        $this->info('Routes published.');
    }

    /**
     * Publish migrations (database migrations)
     */
    private function publishMigrations()
    {
        $this->info('Publishing migrations...');

        $source = __DIR__ . '/../../../database/migrations'; // Source migrations directory
        $destination = database_path('migrations'); // Destination migrations directory

        // Copy migrations directory to the application
        File::copyDirectory($source, $destination);

        $this->info('Migrations published.');
    }
}
