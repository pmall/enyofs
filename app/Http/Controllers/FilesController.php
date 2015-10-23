<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

use App\FilesCollection;

class FilesController extends Controller
{
    private $disk;

    public function __construct(Filesystem $filesystem)
    {
        $this->disk = $filesystem->disk(config('filesystems.default'));
    }

    public function show ($filename = '')
    {
        // If it contains files or directories it is a directory
        $directories = $this->disk->directories($filename);
        $files = $this->disk->files($filename);

        if (count($directories) > 0 or count($files) > 0)
            return view('index', ['directories' => $directories, 'files' => $files]);

        // Get the mimetype
        $mimetype = $this->disk->mimeType($filename);

        // Get the content of the file
        $stream = $this->disk->readStream($filename);

        // Render the file
        header('Content-type: ' . $mimetype);

        while (! feof($stream)) echo fread($stream, 8);

        fclose($stream);
    }

    public function store ($filename, Request $request)
    {
        // Check if a file is sent
        if (! $request->hasFile('file')) return response()->json([
            'error' => 500,
            'message' => 'No file sent.',
        ], 500);

        // Get the file
        $file = $request->file('file');

        // Check if the upload is ok
        if (! $request->file('file')->isValid()) return response()->json([
            'error' => 500,
            'message' => 'Upload error.',
        ], 500);

        // Check if filename already exists
        $existing = $this->disk->exists($filename);

        if ($existing) return response()->json([
            'error' => 403,
            'message' => 'File already exists.',
        ], 403);

        // Get the file mimetype if no mimetype specified
        $mimetype = $request->get('mimetype', $file->getMimeType());

        // Store the file in gridfs
        $stream = fopen($file->getRealPath(), 'r+');
        $this->disk->writeStream($filename, $stream, ['mimetype' => $mimetype]);
        if (is_resource($stream)) fclose($stream);

        // Return the success
        return response()->json(['status' => 'stored', 'filename' => $filename]);
    }

    public function destroy ($filename) {

        // Remove the file from gridfs
        $this->disk->delete($filename);

        // Return the success
        return response()->json(['status' => 'deleted', 'filename' => $filename]);

    }
}
