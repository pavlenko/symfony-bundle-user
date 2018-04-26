<?php

namespace PE\Bundle\UserBundle\Message;

use PE\Bundle\UserBundle\Message\Transport\TransportInterface;
use PE\Bundle\UserBundle\Model\UserInterface;

class Message implements MessageInterface
{
    /**
     * @var TransportInterface[]
     */
    private $transports;

    /**
     * @var array
     */
    private $options;

    /**
     * @param TransportInterface[] $transports
     * @param array                $options
     */
    public function __construct(array $transports, array $options)
    {
        $this->transports = $transports;
        $this->options    = $options;
    }

    /**
     * @inheritDoc
     */
    public function sendToUser(UserInterface $user)
    {
        foreach ($this->transports as $transport) {
            if ($transport->canSendToUser($user)) {
                $transport->sendToUser($user, $this->options[$transport->getType()] ?? []);
            }
        }
    }
}