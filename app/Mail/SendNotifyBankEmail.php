<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotifyBankEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['company'] = $this->details['company'];
        $data['firstname'] = $this->details['firstname'];
        $data['surname'] = $this->details['surname'];
        $data['email'] = $this->details['email'];
        $data['logo'] = $this->details['logo'];
        $data['practice_name'] = $this->details['practice_name'];
        $data['iban'] = $this->details['iban'];
        $data['bic'] = $this->details['bic'];
        
        return $this->view('emails.notify_bank_statement', $data);
    }
}