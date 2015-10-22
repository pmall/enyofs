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
        $filename = '/' . trim($filename, ' /');

        $file = $this->gridfs->findOne($filename);

        if (! $file) abort(404);

        $mime = $file->file['mime'];
        $stream = $file->getResource();

        header('Content-type: ' . $mime);

        while (! feof($stream)) {

            echo fread($stream, 256);

        }
    }

    public function store ($filename, Request $request)
    {
        // Check if sent data are ok
        if (! $request->hasFile('file')) return abort(500, 'No file sent.');
        if (! $request->file('file')->isValid()) return abort(500, 'Upload error.');

        // Format filename
        $filename = '/' . trim($filename, ' /');

        // Check if filename already exists
        $existing = $this->gridfs->findOne($filename);

        if ($existing) abort(403, "File already exists.");

        // Determine the mime type (content type of the request or mime type of
        // the file if not specified)
        $mime = $request->get('mime', $request->file('file')->getMimeType());

        // Store the file in gridfs
        $this->gridfs->storeUpload('file', [
            'filename' => $filename,
            'mime' => $mime,
            'date' => new \MongoDate(),
        ]);

        // Return the filename as success
        return $filename;
    }
}
