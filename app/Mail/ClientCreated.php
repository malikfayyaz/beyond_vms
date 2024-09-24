<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct($client, $user, $password)
    {
        $this->client = $client;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Client Account Created')
            ->view('emails.client_created')
            ->with([
                'client' => $this->client,
                'user' => $this->user,
                'password' => $this->password,
            ]);
    }
}
