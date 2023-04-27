<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MoveFiles;

class MoveFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move files from server to server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $output = (new MoveFiles)->move();
        $this->info($output);
    }
}
