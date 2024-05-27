<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendLoanRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $data;
    public $loanId;
    /**
     * Create a new message instance.
     */
    public function __construct($employee, $data)
    {
        $this->employee = $employee;
        $this->data = $data;
        $this->loanId = $data->id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Loan Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sendLoanRequestMail',
            with: [
                'data' => $this->data,
                'employee' => $this->employee,
                'link' => env('APP_URL') . "/loan-request/approve/" . $this->loanId,

            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
