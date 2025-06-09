<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{
    private $mailer;

    // Correct the constructor name
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer; // Initialize the mailer
    }

    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context // Change type from string to array
    ): void
    {
        //on crée le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context); // context should be an array

        // on envoie le mail
        $this->mailer->send($email);
    }
}