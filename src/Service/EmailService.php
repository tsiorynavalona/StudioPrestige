<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EmailService
{
    private $mailer;
    private $logger;
    private $parameterBag;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->parameterBag = $parameterBag;
    }

    public function sendEmail(string $subject, string $to ,string $textBody, array $context=[], string $template = 'default'): void
    {
        // dd($template);
        $from = $this->parameterBag->get('app.mail_sender');

        if($template == 'contact') {
            $temp = $to;
            $to = $from;
            $from = $temp;
            //permutation des envoyeurs et destinataires
        }

        $email = (new TemplatedEmail())
            ->subject($subject)
            ->to($to)
            ->from($from)
            ->text($textBody)
            // ->html($htmlBody);
            ->context($context)
            ->htmlTemplate("emails/$template.html.twig");

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