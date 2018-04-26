<?php

namespace PE\Bundle\UserBundle\Repository;

use PE\Bundle\UserBundle\Model\UserInterface;

interface UserRepositoryInterface
{
    /**
     * @param mixed $id
     *
     * @return UserInterface|null
     */
    public function findUserByID($id);

    /**
     * @param string $token
     *
     * @return UserInterface|null
     */
    public function findUserByToken($token);

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function findUserByUsername($username);

    /**
     * @param string $email
     *
     * @return UserInterface|null
     */
    public function findUserByEmail($email);

    /**
     * @param string $phone
     *
     * @return UserInterface|null
     */
    public function findUserByPhone($phone);

    /**
     * @return UserInterface
     */
    public function createUser();

    /**
     * @param UserInterface $user
     * @param bool          $flush
     */
    public function updateUser(UserInterface $user, $flush = true);

    /**
     * @param UserInterface $user
     * @param bool          $flush
     */
    public function removeUser(UserInterface $user, $flush = true);

    /**
     * Flush changes
     */
    public function flush();
}