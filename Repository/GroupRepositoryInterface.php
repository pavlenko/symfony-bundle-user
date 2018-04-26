<?php

namespace PE\Bundle\UserBundle\Repository;

use PE\Bundle\UserBundle\Model\GroupInterface;

interface GroupRepositoryInterface
{
    /**
     * @return GroupInterface[]
     */
    public function findGroups();

    /**
     * @param string $id
     *
     * @return GroupInterface|null
     */
    public function findGroupByID($id);

    /**
     * @return GroupInterface
     */
    public function createGroup();

    /**
     * @param GroupInterface $group
     * @param bool           $flush
     */
    public function updateGroup(GroupInterface $group, $flush = true);

    /**
     * @param GroupInterface $group
     * @param bool           $flush
     */
    public function removeGroup(GroupInterface $group, $flush = true);

    /**
     * Flush changes
     */
    public function flush();
}