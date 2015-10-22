<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Http\Request;

class FilesController extends Controller
{
    private $gridfs;

    public function __construct(\MongoGridFS $gridfs)
    {
        $this->gridfs = $gridfs;
    }

    public function index ()
    {
        $files = $this->gridfs->find();

        return view('index', ['files' => $files]);
    }

    public function show ($filename)
    {
        // Quick fix
        $filename = '/' . $filename;

        $file = $this->gridfs->findOne($filename);

        if (! $file) abort(404);

        $mime = $file->file['mime'];
        $stream = $file->getResource();

        header('Content-type: ' . $mime);

        while (! feof($stream)) {

            echo fread($stream, 256);

        }
    }

    public function store (Request $request, $filename)
    {
        return $filename;
    }
}
