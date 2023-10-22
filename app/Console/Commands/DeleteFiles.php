<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dir = public_path();
        $public_path = scandir($dir);

        foreach($public_path as $path)
        {
            $path = str_replace("'","''",$path);
            if($path!='.' && $path!='..')
            {
                if(!is_dir($dir.'/'.$path))
                {
                    unlink($dir.'/'.$path);
                }
            }
        }

        $papers_dir = $dir.'/papers';
        $public_papers_path = scandir($papers_dir);

        foreach($public_papers_path as $path)
        {
            if($path!='.' && $path!='..' && $path!='task_specifics.txt' && $path!='infile_report' && $path!='timeme_import')
            {
                if(!is_dir($papers_dir.'/'.$path))
                {
                    unlink($papers_dir.'/'.$path);
                }
                else{
                    if (\File::exists($papers_dir.'/'.$path)) \File::deleteDirectory($papers_dir.'/'.$path);
                }
            }
        }

        $papers_dir = $dir.'/papers/infile_report';
        $public_papers_path = scandir($papers_dir);

        foreach($public_papers_path as $path)
        {
            if($path!='.' && $path!='..')
            {
                if(!is_dir($papers_dir.'/'.$path))
                {
                    unlink($papers_dir.'/'.$path);
                }
                else{
                    if (\File::exists($papers_dir.'/'.$path)) \File::deleteDirectory($papers_dir.'/'.$path);
                }
            }
        }
        
        \Log::info("deleted files cron is working fine!");
    }
}
