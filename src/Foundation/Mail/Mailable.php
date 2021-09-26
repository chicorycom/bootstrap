<?php


namespace Boot\Foundation\Mail;

use Jenssegers\Blade\Blade;
use Swift_Mailer as Mailer;
use Swift_Message as Email;

class Mailable
{
    protected Blade $view;
    protected Email $email;
    protected Mailer $mailer;

    public function __construct(Mailer $mailer, Email $email, Blade $view)
    {
        $this->view = $view;
        $this->email = $email;
        $this->mailer = $mailer;
    }


    public function subject(string $subject): Mailable
    {
        $this->email->setSubject($subject);

        return $this;
    }

    public function from($address, $name = ''): Mailable
    {
        $this->email->setFrom([$address => $name]);

        return $this;
    }

    public function to($address, $name = ''): Mailable
    {
        $this->email->setTo([$address => $name]);

        return $this;
    }
    public function bcc($address, $name = ''): Mailable
    {
        $this->email->setBcc([$address => $name]);

        return $this;
    }
    public function description($description): Mailable
    {
        $this->email->setDescription($description);

        return $this;
    }

    public function view($path, $with): Mailable
    {
        $template = $this->view->make($path, $with)->render();

        $this->email->setBody($template, 'text/html');

        return $this;
    }

    public function send(): int
    {
        return $this->mailer->send($this->email);
    }
}
