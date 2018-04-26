<?php

namespace PE\Bundle\UserBundle\Model;

interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * @return mixed
     */
    public function getID();

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setID($id);

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param bool $enabled
     *
     * @return self
     */
    public function setEnabled($enabled);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $token
     *
     * @return self
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getPhone();

    /**
     * @param string $phone
     *
     * @return self
     */
    public function setPhone($phone);

    /**
     * @return string
     */
    public function getPlainPassword();

    /**
     * @param string $plainPassword
     *
     * @return self
     */
    public function setPlainPassword($plainPassword);

    // ADDITIONAL METHODS FOR SECURITY USER INTERFACE

    /**
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username);

    /**
     * @param string[] $roles
     *
     * @return self
     */
    public function setRoles(array $roles);

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password);

    /**
     * @param string $salt
     *
     * @return self
     */
    public function setSalt($salt);
}