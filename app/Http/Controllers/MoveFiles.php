<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MoveFiles extends Controller
{
    protected $log_channel;

    public function __construct() {
        // set logging path
        $this->log_channel = Log::build([
          'driver' => 'single',
          'path' => storage_path('apilogs/apilog_'.date("Y-m-d").'.log'),
        ]);
    }

    public function move() {
        $output = null;
        // move files command
        $command = 'rsync -avP --ignore-existing --max-size='.env('FILE_MAX_SIZE').' -e "ssh -i '.env('SERVER_RSA').' -p '.env('SERVER_PORT').'" '.env('SERVER_SRC_USER').'@'.env('SERVER_SRC_IP').':'.env('SERVER_SRC_DIR').env('FILES_MASK').' '.env('SERVER_REM_DIR').' 2>&1';
        // command execution
        exec($command, $output);
        // command execution logging
        Log::stack(['slack', $this->log_channel])->info('Move files from - '.env('SERVER_SRC_USER').'@'.env('SERVER_SRC_IP').':'.env('SERVER_SRC_DIR').env('FILES_MASK').' to '.env('SERVER_REM_DIR').', output: '.json_encode($output));

        return response()->json($output, 200);
    }

    public function multimove() {
        $config = config('movepath');
        if ( empty($config[0]) ) return response()->json('Transfer configuration file not found', 404);

        foreach($config as $cline) {
          $output = null;
          $rm_src = ( $cline[0] == 'mv' ) ? ' --remove-source-files' : '';
          $src_ip = $cline[1];
          $rem_ip = $cline[4];
          $src_dir = $cline[2];
          $rem_dir = $cline[5];
          $file_mask = $cline[3];
          $src_user = $cline[6] ?? env('SERVER_SRC_USER');
          $rem_user = $cline[7] ?? env('SERVER_REM_USER');
          $src_port = $cline[8] ?? env('SSH_PORT');
          $rem_port = $cline[9] ?? env('SSH_PORT');
          $src_key = ( !empty($cline[13]) ) ? file_get_contents($cline[13]) : ( $cline[10] ?? env('SERVER_SRC_RSA_STRING') );
          $rem_key = ( !empty($cline[14]) ) ? file_get_contents($cline[14]) : ( $cline[11] ?? env('SERVER_REM_RSA_STRING') );
          $ief_opt = ( !empty($cline[12]) || env('IGNORE_EXISTING_FILES') ) ? '--ignore-existing' : '';
          $max_size = ( !empty( env('FILE_MAX_SIZE') ) ) ? ' --max-size=' . env('FILE_MAX_SIZE') : '';

          if ( $src_ip == $rem_ip ) { // if the source matches the destination

            $command = 'echo "' . $rem_key . '" | ssh -i /dev/stdin -o StrictHostKeyChecking=no -p ' . $rem_port . ' '. $rem_user .'@'. $rem_ip;

            switch ($cline[0]) {
              case 'cp':
                $rm_src = ($rm_src) ? '-n' : '';
                // command: echo "rsa_key" | ssh -i /dev/stdin -p 1234 user@server cp -n -r -v source_dir distantion_dir
                $command .= ' cp -r -v ' . $rm_src . ' ' . $src_dir . $file_mask . ' ' . $rem_dir;
                break;
              case 'mv':
                // command: echo "rsa_key" | ssh -i /dev/stdin -p 1234 user@server mv -f -v source_dir distantion_dir
                 $command .= ' mv -f -v ' . $src_dir . $file_mask . ' ' . $rem_dir;
                break;
            }

            exec($command, $output);

          } else { // if the source is different from the destination

            // tunel for source server
            $command = 'echo "' . $src_key . '" | ssh -o StrictHostKeyChecking=no -o ClearAllForwardings=yes -i /dev/stdin -L 2222:localhost:' . $src_port . ' -Nf ' . $src_user . '@' . $src_ip;
            // moving files from source to temp local directory
            $command .= '&&rsync -avP ' . $ief_opt . $max_size . $rm_src . ' -e "ssh -o StrictHostKeyChecking=no -p 2222" '. $src_user .'@localhost:' . $src_dir . $file_mask . ' ' . storage_path('tmp/');

            // tunel for remote server
            $command .= '&&echo "' . $rem_key . '" | ssh -o StrictHostKeyChecking=no -o ClearAllForwardings=yes -i /dev/stdin -L 2222:localhost:' . $rem_port . ' -Nf ' . $rem_user . '@' . $rem_ip;
            // moving files from temp local directory to remote server
            $command .= '&&rsync -avP --delete -e "ssh -o StrictHostKeyChecking=no -p 2222" ' . storage_path('tmp/') . $file_mask . ' ' . $rem_user .'@localhost:' . $rem_dir;
            // remove all files from temp local dir
            $command .= '&&rm -r ' . storage_path('tmp/') . '* 2>&1';

            exec($command, $output);
          }
          // command execution logging
          Log::stack(['slack', $this->log_channel])->info('Move files from - '.$src_user.'@'.$src_ip.':'.$src_dir.$file_mask.' to '.$rem_user.'@'.$rem_ip.':'.$rem_dir.', output: '.json_encode($output));

          return response()->json($output, 200);
        }
    }
}
