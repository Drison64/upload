<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Models\Upload;
use App\Models\UrlShortenerUpload;
use App\Services\FileUploadHandler;
use App\Services\UrlShortenerUploadHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadView(Request $request) {
        return view('upload');
    }

    public function deleteView(Request $request, $id) {

        /** @var JsonResponse $status */
        $status = $this->delete($request, $id);

        return view('delete', ['id' => $id, 'status' => $status->status()]);
    }

    public function show(Request $request, $id)
    {
        if (!$id) return response()->json(['status'=>'No id provided.'], 404);

        /** @var Upload $upload */
        $upload = Upload::where("uploadName", $id)->first();

        if (!$upload) return response()->json(['status'=>'Invalid id'], 404);

        $uploadInfo = $upload->uploadInfo()->first();

        $handler = null;

        if ($uploadInfo instanceof FileUpload)
            $handler = new FileUploadHandler();

        else if ($uploadInfo instanceof UrlShortenerUpload)
            $handler = new UrlShortenerUploadHandler();

        if ($handler) return $handler->show($request, $upload);
        return response()->json(['status'=>'No upload found'], 404);
    }

    public function upload_post(Request $request)
    {
        $status = $this->upload($request);

        $data = json_decode($status->content(), true);

        return view('upload', ['status' => $status->status(), 'url' => $data['url'], 'deleteUrl' => $data['deleteUrl']]);
    }

    public function upload(Request $request)
    {
        if (!$request->user()) return response()->json(['status'=>'No token provided.'], 400);

        $handler = null;
        if ($request->hasFile('file'))
            $handler = new FileUploadHandler();

        else if ($request->has('input'))
            if (filter_var($request["input"], FILTER_VALIDATE_URL))
                $handler = new UrlShortenerUploadHandler();
            else $handler = new FileUploadHandler();

        if ($handler) return $handler->upload($request);
        return response()->json(['status'=>'No content uploaded.'], 400);
    }

    public function delete(Request $request, $id) {
        if (!$request->user()) return response()->json(['status'=>'No token provided.'], 400);

        if (!$id) return response()->json(['status'=>'No id provided.'], 400);

        /** @var Upload $upload */
        $upload = Upload::where("uploadName", $id)->first();

        if (!$upload) return response()->json(['status'=>'Invalid id'], 404);

        $uploadInfo = $upload->uploadInfo()->first();

        $handler = null;
        if ($uploadInfo instanceof FileUpload)
            $handler = new FileUploadHandler();

        else if ($uploadInfo instanceof UrlShortenerUploadHandler)
            $handler = new UrlShortenerUploadHandler();

        if ($handler) return $handler->delete($request, $upload);
        return response()->json(['status'=>'No file uploaded.'], 400);

    }

    static function getUrl(Upload $upload, bool $returnDirect = false) : string
    {
        return route("getUpload", ["id" => $upload["uploadName"]]);
    }
}

class GetStoragePathResult {
    public string $path;
    public bool $success;
    public string $fileName;
    public string $fileExtension;

    /**
     * @param string $path
     * @param bool $success
     * @param string $fileName
     * @param string $fileExtension
     */
    public function __construct(bool $success = true, string $path = "", string $fileName = "", string $fileExtension = "")
    {
        $this->path = $path;
        $this->success = $success;
        $this->fileName = $fileName;
        $this->fileExtension = $fileExtension;
    }

    public function getFullPath() : string
    {
        return (str_ends_with($this->path, '/') ? $this->path : $this->path . "/") . $this->fileName . "." . $this->fileExtension;
    }
}

