<?php

namespace App\Services;

use App\Http\Controllers\GetStoragePathResult;
use App\Http\Controllers\UploadController;
use App\Models\FileUpload;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadHandler implements IUploadHandler
{

    public function upload(Request $request)
    {
        $user = $request->user();
        $files = $this->getFiles($request);

        if (sizeof($files) == 0) return response()->json(['status'=>'No file uploaded.'], 400);

        $uploadDate = now();

        $newUploads = [];

        foreach ($files as $file) {
            /** @var UploadedFile $file */
            if (!$file || !$file->isValid()) continue;
            $fileName = hash("sha256", $file->getClientOriginalName() . $file->getContent() . $uploadDate->timestamp . Str::random(8) . $user["id"]);

            if (Upload::where('uploadName', $fileName)->first()) return response()->json(['status'=>'File already uploaded.'], 400);

            $fileUpload = FileUpload::factory()->create([
                'fileExtension' => $file->extension(),
            ]);

            /** @var Upload $newUpload */
            $newUpload = Upload::factory()->create([
                'user_id' => $user["id"],
                'upload_info_type' => FileUpload::class,
                'upload_info_id' => $fileUpload['id'],
                'uploadName' => $fileName,
            ]);

            $uploadPath = $this->getStoragePath($newUpload);

            Storage::drive("uploads")->putFileAs(
                $uploadPath->path
                , $file,
                $fileName . "." . $file->extension()
            );

            $newUploads[] = $newUpload;
        }

        return response()->json(['status'=>'You have successfully uploaded an image.', 'url'=>UploadController::getUrl($newUploads[0], true), 'deleteUrl'=>route('delete', ['id' => $newUploads[0]['uploadName']])]);
    }

    public function show(Request $request, $upload)
    {
        $fileExists = Storage::drive("uploads")->exists($this->getStoragePath($upload)->getFullPath());
        if (!$fileExists) return response()->json(['status'=>'File not found.'], 404);
        $path = Storage::drive("uploads")->path($this->getStoragePath($upload)->getFullPath());
        return response()->file($path);
    }

    public function delete(Request $request, $upload)
    {
        $user = $request->user();
        if (!$upload) return response()->json(['status'=>'File not found.'], 404);

        if ($upload["user_id"] != $user["id"]) return response()->json(['status'=>'You do not have permission to delete this file.'], 403);

        $uploadPath = $this->getStoragePath($upload);

        if (!Storage::drive("uploads")->exists($uploadPath->getFullPath())) return response()->json(['status'=>'File not found.'], 404);

        Storage::drive("uploads")->delete($uploadPath->getFullPath());

        $upload->uploadInfo()->first()->delete();
        $upload->delete();

        return response()->json(['success'=>'You have successfully deleted the image.']);
    }

    static function getStoragePath(Upload $upload) : GetStoragePathResult
    {
        /** @var User $user */
        $user = User::where('id', $upload["user_id"])->first();

        if (!$user) return new GetStoragePathResult(false);

        return new GetStoragePathResult(true,
            '/' . $user["id"] . "/" . $upload["created_at"]->year . "/" . $upload["created_at"]->month . "/"
            , $upload["uploadName"]
            , $upload->uploadInfo()->first()["fileExtension"]
        );
    }

    static function getFiles(Request $request) : array
    {
        $files = [];
        foreach ($request->keys() as $key) {
            if (str_contains($key, "file")) $files[] = $request->file($key);
        }
        return $files;
    }

}
