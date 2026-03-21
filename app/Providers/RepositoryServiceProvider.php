<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Registra automáticamente todos los repositorios Eloquent enlazando
     * cada implementación con su interfaz correspondiente en el contenedor.
     * @return void
     */
    public function register(): void
    {
        $files = glob(app_path('Repositories/Eloquent/*.php'));

        foreach ($files as $file) {
            $class = 'App\\Repositories\\Eloquent\\' . basename($file, '.php');
            $interface = $this->resolveInterface($class);

            if ($interface && interface_exists($interface)) {
                $this->app->bind($interface, $class);
            }
        }
    }

    /**
     * Deriva el nombre de la interfaz a partir del nombre de la clase del repositorio.
     * Ejemplo: EloquentCategoriaRepository → App\Contracts\Repositories\CategoriaRepositoryInterface
     * @param string $class Nombre completo de la clase del repositorio.
     * @return string|null
     */
    private function resolveInterface(string $class): ?string
    {
        // EloquentCategoriaRepository → CategoriaRepositoryInterface
        $name = str_replace('App\\Repositories\\Eloquent\\Eloquent', '', $class);

        return 'App\\Contracts\\Repositories\\' . $name . 'Interface';
    }
}
