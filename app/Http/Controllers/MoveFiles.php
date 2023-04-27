<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MoveFiles extends Controller
{
    public function move() {
        $output = null;
        $command = 'rsync -avP --ignore-existing -e "ssh -i '.env('SERVER_SSHKEY').' -p '.env('_SERVER_PORT').'" '.env('SERVER_USER').'@'.env('SERVER_SRC_IP').':'.env('SERVER_SRC_DIR').env('FILES_MASK').' '.env('SERVER_POINT_DIR').' 2>&1';
        exec($command, $output);
        return response()->json($output, 200);
    }
}
