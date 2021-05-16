<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\Match;
use Log;

class CheckMatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_match';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $matchService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Match $matchService)
    {
        parent::__construct();
        $this->matchService = $matchService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info(__CLASS__);
        $this->matchService->updateStatus();
    }
}
