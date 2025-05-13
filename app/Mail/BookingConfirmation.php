<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('تأكيد الحجز')
                    ->view('emails.booking_confirmation')
                    ->with([
                        'guest_name' => $this->booking->guest_name,
                        'check_in' => $this->booking->check_in,
                        'check_out' => $this->booking->check_out,
                        'total_amount' => $this->booking->total_amount,
                    ]);
    }
}
