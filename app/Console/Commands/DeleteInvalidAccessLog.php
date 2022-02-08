<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AccessLog;

class DeleteInvalidAccessLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete_invalid_access_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Invalid Access Log';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $auto_delete = $this->deleteInvalidAccessLog();
        $this->info($auto_delete . ' Logs');
    }

    public function deleteInvalidAccessLog(){
        $count = AccessLog::where('MsgID','!=','1')->count();
        AccessLog::where('MsgID','!=','1')->delete();
        return $count;
    }
}
