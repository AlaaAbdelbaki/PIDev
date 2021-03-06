<?php

namespace AppBundle\Repository;

/**
 * videoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class videoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findRanks()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT  v.video_id 
  FROM
  votes v
 GROUP by v.video_id
 ORDER by count(v.video_id) DESC
 LIMIT 3';
        $stmt = $conn->prepare($sql);

        $stmt->execute();

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
    }
}
