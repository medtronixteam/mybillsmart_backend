<?php namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $twoFactorCode;

    /**
     * Create a new message instance.
     */
    public function __construct($twoFactorCode)
    {
        $this->twoFactorCode = $twoFactorCode;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your 2FA Code - myBillsmart')
                    ->view('emails.two_factor_code'); // Reference to the Blade template
    }
}
