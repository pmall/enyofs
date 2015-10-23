<?php

namespace App\Providers;

use MongoClient;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Filesystem\Factory as FilesystemManager;

use League\Flysystem\Filesystem;
use League\Flysystem\GridFS\GridFSAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->call([$this, 'registerGridfsFilesystem']);
    }

    public function registerGridfsFilesystem(FilesystemManager $filesystem)
    {
        $filesystem->extend('gridfs', function ($app, $config) {

            $fs_host = $config['host'];
            $fs_port = $config['port'];
            $fs_database = $config['database'];

            $mongo = new MongoClient('mongodb://' . $fs_host . ':' . $fs_port);

            $db = $mongo->selectDB($fs_database);

            $gridfs = $db->getGridFS();

            return new Filesystem(new GridFSAdapter($gridfs));

        });
    }
}
