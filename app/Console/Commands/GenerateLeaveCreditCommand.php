<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class GenerateLeaveCreditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-leave-credit-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Leave Credits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employees = Employee::where('is_discontinued', false)->whereIn('status', ['Active']);
    }
}
