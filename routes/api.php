<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api_token')->get('/movefiles', function (Request $request) {
    $output = null;
    // $command = 'scp -C -i '.env('SERVER_SSHKEY').' '.env('SERVER_SRC_DIR').env('FILES_MASK').' '.env('SERVER_POINT_USER').'@'.env('SERVER_POINT_IP').':'.env('SERVER_POINT_DIR').' 2>&1';
    $command = 'rsync -avP --ignore-existing -e "ssh -i '.env('SERVER_SSHKEY').' -p '.env('SERVER_PORT').'" '.env('SERVER_USER').'@'.env('SERVER_SRC_IP').':'.env('SERVER_SRC_DIR').env('FILES_MASK').' '.env('SERVER_POINT_DIR').' 2>&1';
    exec($command, $output);
    return response()->json($output, 200);
});

