<?php
namespace App\Scubaya\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Contracts\Sendable;


class SendMailHelper
{
    protected $sendable;

    public function __construct($sendable)
    {
        $this->sendable = $sendable;
    }

    public function sendMail()
    {
        Mail::send($this->template, [
            'msg' => $this->message
        ] ,
        function($m) {
            $m->from($this->sender, "Scubaya Team");
            $m->to($this->receiver)->subject($this->subject);
        });
    }

    public function send()
    {
        if (!empty($this->sendable->getContent()))
        {
            $success = $this->sendToProvider();

            return $success;
        }
        else
        {
            throw new \RuntimeException('Message is empty, cannot send!');
        }
    }

    private function sendToProvider()
    {
        Mail::send('email.default', [ 'content' => $this->sendable->getContent()], function ($message)
        {
            $sender = $this->sendable->getSender();

            $message->subject($this->sendable->getSubject());
            $message->from($sender['email'], $sender['name']);
            $message->to($this->sendable->getRecipient());

            foreach ($this->sendable->getAttachments() as $file)
            {
                $message->attach($file);
            }
        });

        return true;
    }
}