<?php

namespace PE\Bundle\UserBundle\Message;

use PE\Bundle\UserBundle\Model\UserInterface;

interface MessageInterface
{
    /**
     * Send some message to user via email and/or sms
     *
     * @param UserInterface $user
     */
    public function sendToUser(UserInterface $user);
}