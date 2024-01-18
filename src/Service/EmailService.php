<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EmailService
{
    private $mailer;
    private $logger;
    // private $flashBag;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        // $this->flashBag = $flashBag;
    }

    public function sendEmail(string $subject, string $from, string $textBody): void
    {
        $email = (new Email())
            ->subject($subject)
            // ->from($from)
            ->from($from)
            ->text($textBody);
            // ->html($htmlBody);

            // try {
            $this->mailer->send($email);
            // } catch (TransportExceptionInterface $e) {
            //     $this->logger->error('Error sending email: ' . $e->getMessage());

                // Display an error message to the user
            //     $errorMessage = 'An error occurred while sending the email. Please try again later.';
            //     // $this->flashBag->add('error', $errorMessage);
            
            // }

        // $this->mailer->send($email);
    }
}