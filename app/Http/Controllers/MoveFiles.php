<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MoveFiles extends Controller
{
    public function move() {
        // set logging path
        $channel = Log::build([
          'driver' => 'single',
          'path' => storage_path('apilogs/apilog_'.date("Y-m-d").'.log'),
        ]);

        $output = null;
        // move files command
        $command = 'rsync -avP --ignore-existing --max-size='.env('MAX_SIZE').' -e "ssh -i '.env('SERVER_SSHKEY').' -p '.env('_SERVER_PORT').'" '.env('SERVER_USER').'@'.env('SERVER_SRC_IP').':'.env('SERVER_SRC_DIR').env('FILES_MASK').' '.env('SERVER_POINT_DIR').' 2>&1';
        // command execution
        exec($command, $output);
        // command execution logging
        Log::stack(['slack', $channel])->info('Move files from - '.env('SERVER_USER').'@'.env('SERVER_SRC_IP').':'.env('SERVER_SRC_DIR').env('FILES_MASK').' to '.env('SERVER_POINT_DIR').', output: '.json_encode($output));

        return response()->json($output, 200);
    }
}
