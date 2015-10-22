<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilesController extends Controller
{
    private $gridfs;

    public function __construct(\MongoGridFS $gridfs)
    {
        $this->gridfs = $gridfs;
    }

    public function show ($filename)
    {
        return $filename;
    }

    public function store (Request $request, $filename)
    {
        return $filename;
    }
}
