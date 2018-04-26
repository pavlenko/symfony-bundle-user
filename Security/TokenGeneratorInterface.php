<?php

namespace PE\Bundle\UserBundle\Security;

interface TokenGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}