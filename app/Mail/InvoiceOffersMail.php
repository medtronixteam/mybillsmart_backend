<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Offer;
class InvoiceOffersMail extends Mailable
{
    use Queueable, SerializesModels;
    public $offers;
    public $pdf;
    public $company;
    /**
     * Create a new message instance.
     */
    public function __construct($offers, $pdf, $company)
    {
         $this->offers = $offers;
        $this->pdf = $pdf;
        $this->company = $company;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Offers',
        );
    }
 public function build()
    {
        return $this->view('invoice_offers_email')
            ->with(['offers' => $this->offers])
            ->attachData($this->pdf->output(), 'offers.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'invoice_offers_email',
    //         // with: ['offers' => $this->offers],
    //     );
    // }

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
