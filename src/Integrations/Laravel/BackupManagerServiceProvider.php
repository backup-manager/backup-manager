<?php namespace BigName\BackupManager\Integrations\Laravel;

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Config\ConfigFileNotFound;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Integrations\Laravel\Questions\QuestionProvider;
use BigName\BackupManager\Manager;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Process;

/**
 * Class BackupManagerServiceProvider
 * @package BigName\BackupManager\Integrations\Laravel
 */
class BackupManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('heybigname/backup-manager', 'backup-manager', __DIR__.'/../../..');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//        $this->registerManager();
        $this->registerFilesystemProvider();
        $this->registerDatabaseProvider();
        $this->registerShellProcessor();
        $this->registerArtisanCommands();
    }

    /**
     *
     */
    private function registerManager()
    {
        $this->app->bind('BigName\BackupManager\Manager', function() {
            return new Manager(
                $this->getConfigPath('storage'),
                $this->getConfigPath('database')
            );
        });
    }

    /**
     *
     */
    private function registerFilesystemProvider()
    {
        $this->app->bind('BigName\BackupManager\Filesystems\FilesystemProvider', function() {
            return new FilesystemProvider(new Config($this->getConfigPath('storage')));
        });
    }

    /**
     *
     */
    private function registerDatabaseProvider()
    {
        $this->app->bind('BigName\BackupManager\Databases\DatabaseProvider', function() {
            return new DatabaseProvider(new Config($this->getConfigPath('database')));
        });
    }

    /**
     *
     */
    private function registerShellProcessor()
    {
        $this->app->bind('BigName\BackupManager\ShellProcessing\ShellProcessor', function() {
            return new ShellProcessor(new Process(''));
        });
    }

    /**
     *
     */
    private function registerArtisanCommands()
    {
        $this->commands([
            'BigName\BackupManager\Integrations\Laravel\ManagerBackupCommand',
            'BigName\BackupManager\Integrations\Laravel\ManagerRestoreCommand',
        ]);
    }

    /**
     * @param $name
     * @return string
     * @throws \BigName\BackupManager\Config\ConfigFileNotFound
     */
    private function getConfigPath($name)
    {
        $path = app_path("config/packages/heybigname/backup-manager/config/$name.php");
        if ( ! file_exists($path)) {
            throw new ConfigFileNotFound('The configuration file "' . $path . '" could not be found.');
        }
        return $path;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'BigName\BackupManager\Filesystems\FilesystemProvider',
            'BigName\BackupManager\Databases\DatabaseProvider',
            'BigName\BackupManager\ShellProcessing\ShellProcessor',
        ];
    }
}
