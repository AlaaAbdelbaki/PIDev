<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * competition_participant
 *
 * @ORM\Table(name="competition_participant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\competition_participantRepository")
 */
class competition_participant
{
    /**
     * @return mixed
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * @param mixed $competition
     */
    public function setCompetition($competition)
    {
        $this->competition = $competition;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="competition", inversedBy="competition_participant")
     * @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     */
    private $competition;
    /**
     * @ORM\ManyToOne(targetEntity="user", inversedBy="competition_participant")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="participation_date", type="date")
     */
    private $participationDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set participationDate
     *
     * @param \DateTime $participationDate
     *
     * @return competition_participant
     */
    public function setParticipationDate($participationDate)
    {
        $this->participationDate = $participationDate;

        return $this;
    }

    /**
     * Get participationDate
     *
     * @return \DateTime
     */
    public function getParticipationDate()
    {
        return $this->participationDate;
    }
}

