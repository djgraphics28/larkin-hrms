<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendLeaveRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $data;
    public $leaveId;

    /**
     * Create a new message instance.
     */
    public function __construct($employee, $data)
    {
        $this->employee = $employee;
        $this->data = $data;
        $this->leaveId = $data->id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Leave Request Approval Needed for',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sendLeaveRequestMail',
            with: [
                'data' => $this->data,
                'employee' => $this->employee,
                'link' => "https://larkin-hrms.com/leave-request/approve/".$this->leaveId,

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
