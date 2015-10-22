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
        // Format filename
        $filename = '/' . trim($filename, ' /');

        // Get the file from gridfs
        $file = $this->gridfs->findOne($filename);

        if (! $file) return response('File not found.', 404);

        // Get file's mime and file handler
        $mime = $file->file['mime'];
        $stream = $file->getResource();

        // Render the file
        header('Content-type: ' . $mime);

        while (! feof($stream)) {

            echo fread($stream, 256);

        }
    }

    public function store ($filename, Request $request)
    {
        // Check if sent data are ok
        if (! $request->hasFile('file')) return response()->json([
            'error' => 500,
            'message' => 'No file sent.',
        ], 500);

        if (! $request->file('file')->isValid()) return response()->json([
            'error' => 500,
            'message' => 'Upload error.',
        ], 500);

        // Format filename
        $filename = '/' . trim($filename, ' /');

        // Check if filename already exists
        $existing = $this->gridfs->findOne($filename);

        if ($existing) return response()->json([
            'error' => 403,
            'message' => 'File already exists.',
        ], 403);

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
