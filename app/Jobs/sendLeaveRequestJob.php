<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\SendLeaveRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class sendLeaveRequestJob implements ShouldQueue
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
        Mail::to('darwin.ibay30@gmail.com')->send(new SendLeaveRequest($this->employee, $this->data));
    }
}
