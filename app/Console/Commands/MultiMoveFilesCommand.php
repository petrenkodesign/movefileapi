<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\MoveFiles;

class MultiMoveFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:multifiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'File transfer between different servers according to the configuration from the array';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $output = (new MoveFiles)->multimove();
        $this->info($output);
    }
}
