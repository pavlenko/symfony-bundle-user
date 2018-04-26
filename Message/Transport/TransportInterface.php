<?php

namespace PE\Bundle\UserBundle\Message\Transport;

use PE\Bundle\UserBundle\Model\UserInterface;

interface TransportInterface
{
    /**
     * Check if support send this message to user
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function canSendToUser(UserInterface $user): bool;

    /**
     * Send message to user with message options
     *
     * @param UserInterface $user
     * @param array         $options
     */
    public function sendToUser(UserInterface $user, array $options): void;

    /**
     * Get transport type
     *
     * @return string
     */
    public function getType(): string;
}