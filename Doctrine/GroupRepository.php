<?php

namespace PE\Bundle\UserBundle\Doctrine;

use PE\Bundle\UserBundle\Model\GroupInterface;
use PE\Bundle\UserBundle\Repository\GroupRepositoryInterface;

class GroupRepository extends AbstractRepository implements GroupRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findGroups()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findGroupByID($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @inheritDoc
     */
    public function createGroup()
    {
        $class = $this->getClass();
        return new $class;
    }

    /**
     * @inheritDoc
     */
    public function updateGroup(GroupInterface $group, $flush = true)
    {
        $manager = $this->getManager();
        $manager->persist($group);

        if ($flush) {
            $manager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function removeGroup(GroupInterface $group, $flush = true)
    {
        $manager = $this->getManager();
        $manager->remove($group);

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
}