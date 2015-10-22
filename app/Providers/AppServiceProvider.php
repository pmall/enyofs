<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\MongoGridFS::class, function () {

            $fs_host = env('FS_HOST', 'mongodb://localhost');
            $fs_port = env('FS_PORT', '27017');
            $fs_database = env('FS_DATABASE');

            if (! $fs_database) abort(500, 'Database name not set.');

            $mongo = new \MongoClient(implode(':', [$fs_host, $fs_port]));

            $db = $mongo->selectDB($fs_database);

            return $db->getGridFS();

        });
    }
}
