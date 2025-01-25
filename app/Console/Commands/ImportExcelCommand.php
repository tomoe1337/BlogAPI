<?php

namespace App\Console\Commands;

use App\Imports\PostsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        ini_set('memory_limit', '-1');
        Excel::import(new PostsImport, public_path('excel/posts.xlsx'));

        dd('finished');

    }
}
