<?php

namespace PE\Bundle\UserBundle\Message\Transport;

use PE\Bundle\UserBundle\Model\UserInterface;

class SwiftMailerTransport implements TransportInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer     $mailer
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig   = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public function canSendToUser(UserInterface $user): bool
    {
        return !empty($user->getEmail());
    }

    /**
     * @inheritDoc
     *
     * @throws \Throwable
     */
    public function sendToUser(UserInterface $user, array $options): void
    {
        $template = $this->twig->load($options['template']);

        $context = ['user' => $user];

        $subject  = $template->renderBlock('subject', $context);
        $bodyText = $template->renderBlock('body_text', $context);
        $bodyHTML = '';

        if ($template->hasBlock('body_html', $context)) {
            $bodyHTML = $template->renderBlock('body_html', $context);
        }

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($options['sender'])
            ->setTo($user->getEmail());

        if (!empty($bodyHTML)) {
            $message
                ->setBody($bodyHTML, 'text/html')
                ->addPart($bodyText, 'text/plain');
        } else {
            $message->setBody($bodyText);
        }

        $this->mailer->send($message);
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'email';
    }
}