<?php

namespace PE\Bundle\UserBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use PE\Bundle\UserBundle\Model\UserInterface;
use PE\Bundle\UserBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @param ObjectManager           $objectManager
     * @param string                  $class
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(ObjectManager $objectManager, $class, EncoderFactoryInterface $encoderFactory)
    {
        parent::__construct($objectManager, $class);
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @inheritDoc
     */
    public function findUserByID($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @inheritDoc
     */
    public function findUserByToken($token)
    {
        return $this->getRepository()->findOneBy(['token' => $token]);
    }

    /**
     * @inheritDoc
     */
    public function findUserByUsername($username)
    {
        return $this->getRepository()->findOneBy(['username' => $username]);
    }

    /**
     * @inheritDoc
     */
    public function findUserByEmail($email)
    {
        return $this->getRepository()->findOneBy(['email' => $email]);
    }

    /**
     * @inheritDoc
     */
    public function findUserByPhone($phone)
    {
        return $this->getRepository()->findOneBy(['phone' => $phone]);
    }

    /**
     * @inheritDoc
     */
    public function createUser()
    {
        $class = $this->getClass();
        return new $class();
    }

    /**
     * @inheritDoc
     */
    public function updateUser(UserInterface $user, $flush = true)
    {
        $this->updateUserPassword($user);

        $manager = $this->getManager();
        $manager->persist($user);

        if ($flush) {
            $manager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function removeUser(UserInterface $user, $flush = true)
    {
        $manager = $this->getManager();
        $manager->remove($user);

        if ($flush) {
            $manager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function flush()
    {
        $this->getManager()->flush();
    }

    protected function updateUserPassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        if ($encoder instanceof BCryptPasswordEncoder) {
            $user->setSalt(null);
        } else {
            $salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
            $user->setSalt($salt);
        }

        $user->setPassword($encoder->encodePassword($plainPassword, $user->getSalt()));
        $user->eraseCredentials();
    }
}