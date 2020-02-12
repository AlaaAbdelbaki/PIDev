<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * complaint
 *
 * @ORM\Table(name="complaint")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\complaintRepository")
 */
class complaint
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
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


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
     * @return complaint
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
     * Set content
     *
     * @param string $content
     *
     * @return complaint
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get contentA
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}

