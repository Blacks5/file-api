<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    public function __construct()
    {
        //
    }

    public function tempGet(Request $request)
    {
        return Response::success([
            'phone' => $request->input('phone', null),
        ]);
    }

    public function tempPost(Request $request)
    {
        return Response::success([
            'user_name'=> $request->input('user_name'),
        ]);
    }

    public function tempPut(Request $request)
    {
        return Response::success([
            'bank_name'=> $request->input('bank_name'),
        ]);
    }

    public function fileCreate(Request $request)
    {
        $r = $request->file('img_1')->move(storage_path('app'), time());
        return Response::success([
            'real_path' => $r->getRealPath(),
        ]);
    }
}
