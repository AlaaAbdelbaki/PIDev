<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert ;

/**
 * competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\competitionRepository")
 */
class competition
{
    /**
     * @return ArrayCollection
     */
    public function getCompetitionParticipant()
    {
        return $this->competition_participant;
    }

    /**
     * @param ArrayCollection $competition_participant
     */
    public function setCompetitionParticipant($competition_participant)
    {
        $this->competition_participant = $competition_participant;
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
     * @ORM\OneToMany(targetEntity="competition_participant", mappedBy="competition" , orphanRemoval=true)
     */
    private $competition_participant;

    public function __construct()
    {
        $this->competition_participant = new ArrayCollection();
        $this->competitionDate=new \DateTime();
    }



    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var \DateTime
     * @Assert\GreaterThan("now")
     * @ORM\Column(name="competition_date", type="datetime")
     */
    private $competitionDate;

    /**
     * @var \DateTime
     *@Assert\GreaterThan(propertyPath="competition_date")
     * @ORM\Column(name="competition_end_date", type="datetime")
     */
    private $competitionEndDate;


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
     * Set subject
     *
     * @param string $subject
     *
     * @return competition
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set competitionDate
     *
     * @param \DateTime $competitionDate
     *
     * @return competition
     */
    public function setCompetitionDate($competitionDate)
    {
        $this->competitionDate = $competitionDate;

        return $this;
    }

    /**
     * Get competitionDate
     *
     * @return \DateTime
     */
    public function getCompetitionDate()
    {
        return $this->competitionDate;
    }

    /**
     * Set competitionEndDate
     *
     * @param \DateTime $competitionEndDate
     *
     * @return competition
     */
    public function setCompetitionEndDate($competitionEndDate)
    {
        $this->competitionEndDate = $competitionEndDate;

        return $this;
    }

    /**
     * Get competitionEndDate
     *
     * @return \DateTime
     */
    public function getCompetitionEndDate()
    {
        return $this->competitionEndDate;
    }
    private $winner;

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }
}

