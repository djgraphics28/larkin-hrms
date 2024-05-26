<?php

namespace App\Jobs;

use App\Mail\SendLoanRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendLoanRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $employee;
    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($employee, $data)
    {
        $this->employee = $employee;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('darwin.ibay30@gmail.com')->send(new SendLoanRequest($this->employee, $this->data));
    }
}
