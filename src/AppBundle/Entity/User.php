<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Birthday", type="date"  ,nullable=true)
     */
    private $birthday;

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }

    /**
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param string $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * @param string $telephoneNumber
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getProfilePic()
    {
        return $this->profilePic;
    }

    /**
     * @param string $profilePic
     */
    public function setProfilePic($profilePic)
    {
        $this->profilePic = $profilePic;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="profile_pic", type="string", length=500  ,nullable=true)
     */
    private $profilePic;
    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255  ,nullable=true)
     */
    private $sexe;
    /**
     * @var string
     *
     * @ORM\Column(name="telephone_number", type="string", length=255  ,nullable=true)
     */
    private $telephoneNumber;
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255  ,nullable=true)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255  ,nullable=true)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255  ,nullable=true)
     */
    private $firstName;
    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text" ,nullable=true)
     */
    private $bio;

    /**
     * @ORM\OneToMany(targetEntity="comment", mappedBy="user" , orphanRemoval=true)
     */
    private $comments;
    /**
     * @ORM\OneToMany(targetEntity="event_participant", mappedBy="user" , orphanRemoval=true)
     */
    private $event_participants;

    /**
     * @ORM\OneToMany(targetEntity="review", mappedBy="user" , orphanRemoval=true)
     */
    private $review;
    /**
     * @ORM\OneToMany(targetEntity="complaint", mappedBy="user" , orphanRemoval=true)
     */
    private $complaint;
    /**
     * @ORM\OneToMany(targetEntity="subscription", mappedBy="sub" , orphanRemoval=true)
     */
    private $subedtos;
    /**
     * @ORM\OneToMany(targetEntity="subscription", mappedBy="subedto" , orphanRemoval=true)
     */
    private $subs;
    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

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
     * @return ArrayCollection
     */
    public function getEventParticipants()
    {
        return $this->event_participants;
    }

    /**
     * @param ArrayCollection $event_participants
     */
    public function setEventParticipants($event_participants)
    {
        $this->event_participants = $event_participants;
    }
    /**

     * @OneToMany(targetEntity="video", mappedBy="owner")
     */
    private $videos;

    /**
     * @return ArrayCollection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * @param ArrayCollection $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }




    /**
     * @ORM\OneToMany(targetEntity="competition_participant", mappedBy="user" , orphanRemoval=true)
     */
    private $competition_participant;

    public function __construct()
    { parent::__construct();
        $this->comments = new ArrayCollection();
        $this->event_participants = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->competition_participant = new ArrayCollection();
        $this->review = new ArrayCollection();
        $this->complaint = new ArrayCollection();
        $this->subedtos = new ArrayCollection();
        $this->subs = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getComplaint()
    {
        return $this->complaint;
    }

    /**
     * @param ArrayCollection $complaint
     */
    public function setComplaint($complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * @return ArrayCollection
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param ArrayCollection $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubedtos()
    {
        return $this->subedtos;
    }

    /**
     * @param ArrayCollection $subedtos
     */
    public function setSubedtos($subedtos)
    {
        $this->subedtos = $subedtos;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubs()
    {
        return $this->subs;
    }

    /**
     * @param ArrayCollection $subs
     */
    public function setSubs($subs)
    {
        $this->subs = $subs;
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

