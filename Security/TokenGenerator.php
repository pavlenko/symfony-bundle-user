<?php

namespace PE\Bundle\UserBundle\Security;

class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(): string
    {
        $token = base64_encode(random_bytes(64));
        $token = strtr($token, '+/', '-_');
        $token = trim($token, '=');
        $token = substr($token, 0, 10);

        return $token;
    }
}