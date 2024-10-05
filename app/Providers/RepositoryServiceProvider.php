<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * RepositoryServiceProvider constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(\Illuminate\Contracts\Foundation\Application $app)
    {
        // call the parent
        parent::__construct($app);

    }

    /**
     * Register the application services.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function register()
    {
        // get the base namespace
        $baseNamespace = 'App\Repositories\\';

        // get all the files in the base path
        $repositoryFiles = \File::files(app_path('Repositories'));

        // bind the repository to the correct interface for the api namespace
        $this->bindRepositoriesToInterfaces($repositoryFiles, $baseNamespace);
    }

    /**
     * Bind the given repository files for a given namespace to the implemented interfaces
     *
     * @param   array                       $repositoryFiles    The repository files to bind
     * @param   string                      $baseNamespace      The base namespace for the repositories
     *
     * @throws  \ReflectionException
     */
    protected function bindRepositoriesToInterfaces(array $repositoryFiles, string $baseNamespace) : void
    {
        foreach($repositoryFiles as $repositoryFile) {

            // get the repository class namespace
            $repositoryNamespace = $baseNamespace . $repositoryFile->getBasename('.' . $repositoryFile->getExtension());

            // create a repository class to evaluate the repository
            $repositoryReflectionClass = new \ReflectionClass($repositoryNamespace);

            // try resolving the interface namespace for the repository
            $interface = $this->resolveInterface(array_reverse($repositoryReflectionClass->getInterfaces()), $baseNamespace);

            // if there is no interface, continue to the next iteration
            if (is_null($interface)) {
                continue;
            }

            // bind the repository to the correct repository interface
            $this->app->bind($interface->getName(), $repositoryReflectionClass->getName());
        }
    }

    /**
     * Helper method to get the interface information from a list of interfaces
     *
     * @param array $interfaces Array of ReflectionClass information for all the available interfaces
     * @param string $baseNamespace The base namespace
     * @return \ReflectionClass|null The reflection class information for the namespace if available, otherwise null
     */
    protected function resolveInterface(array $interfaces, string $baseNamespace) : ?\ReflectionClass
    {
        // if there are no interfaces, return null
        if (count($interfaces) == 0) {
            return null;
        }

        // iterate over the interfaces, to check if there is one in the correct namespace
        foreach ($interfaces as $interface) {
            if (Str::startsWith($interface->getName(), $baseNamespace . 'Contracts')) {
                return $interface;
            }
        }

        // no interface found, return null
        return null;
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
