<?php

namespace App\Services;

use Illuminate\Http\Request;

interface IUploadHandler
{
    public function upload(Request $request);
    public function show(Request $request, $upload);
    public function delete(Request $request, $upload);
}
