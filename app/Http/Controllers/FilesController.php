<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 14:05
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function show($uuid)
    {

    }

    public function store(Request $request)
    {
        dd(config('filesystems'));
        $path = '2017/10/'.rand(1000,9999).'.jpg';
        $file = $request->file('image');
        $filePath = $file->getRealPath();
        $mimeType = $file->getClientMimeType();

        $file = Storage::disk('oss')->putFile($path,$filePath,['ContentType'=>$mimeType]);


        dd($file);
    }
}