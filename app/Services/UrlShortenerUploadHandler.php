<?php

namespace App\Services;

use App\Http\Controllers\UploadController;
use App\Models\Upload;
use App\Models\UrlShortenerUpload;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UrlShortenerUploadHandler implements IUploadHandler
{
    public function upload(Request $request)
    {
        $user = $request->user();
        $content = $request["input"];

        $uploadDate = now();

        /** @var UploadedFile $file */
        $uploadName = hash("sha256", $content . $uploadDate->timestamp . Str::random(8) . $user["id"]);

        if (Upload::where('uploadName', $uploadName)->first()) return response()->json(['status'=>'File already uploaded.']);

        $urlShortenerUpload = UrlShortenerUpload::factory()->create([
            'targetUrl' => $content,
        ]);

        /** @var Upload $newUpload */
        $newUpload = Upload::factory()->create([
            'user_id' => $user["id"],
            'upload_info_type' => UrlShortenerUpload::class,
            'upload_info_id' => $urlShortenerUpload['id'],
            'uploadName' => $uploadName,
        ]);

        return response()->json(['status'=>'You have successfully uploaded an image.', 'url'=>UploadController::getUrl($newUpload, true), 'deleteUrl'=>route('delete', ['id' => $newUpload['uploadName']])]);
    }

    public function show(Request $request, $upload)
    {
        if (!$upload) return response()->json(['status'=>'Url not found.']);

        $uploadInfo = $upload->uploadInfo()->first();

        if (!$uploadInfo) return response()->json(['status'=>'Url not found.']);

        return response()->redirectTo($uploadInfo["targetUrl"]);
    }

    public function delete(Request $request, $upload)
    {
        $user = $request->user();
        if (!$upload) return response()->json(['status'=>'Url not found.']);

        if ($upload["user_id"] != $user["id"]) return response()->json(['status'=>'You do not have permission to delete this url.']);

        $upload->uploadInfo()->first()->delete();
        $upload->delete();

        return response()->json(['success'=>'You have successfully deleted the url.']);
    }


}
