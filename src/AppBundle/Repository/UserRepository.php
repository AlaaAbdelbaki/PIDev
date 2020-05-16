<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u
                FROM AppBundle:User u
                WHERE u.username LIKE :str'
            )
            ->setParameter('str', '%' . $str . '%')
            ->getResult();
    }


}
