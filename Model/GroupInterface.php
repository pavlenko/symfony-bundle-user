<?php

namespace PE\Bundle\UserBundle\Model;

interface GroupInterface
{
    /**
     * @return string
     */
    public function getID();

    /**
     * @param string $id
     *
     * @return self
     */
    public function setID($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * @return string[]
     */
    public function getRoles();

    /**
     * @param string[] $roles
     *
     * @return self
     */
    public function setRoles(array $roles);
}