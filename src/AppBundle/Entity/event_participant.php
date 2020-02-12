<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * event_participant
 *
 * @ORM\Table(name="event_participant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\event_participantRepository")
 */
class event_participant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="participation_date", type="date")
     */
    private $participationDate;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
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
     * @ORM\ManyToOne(targetEntity="event", inversedBy="event_participants")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="event_participants")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
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
     * @return event_participant
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

