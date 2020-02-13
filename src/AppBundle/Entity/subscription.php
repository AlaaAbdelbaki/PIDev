<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * subscription
 *
 * @ORM\Table(name="subscription")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\subscriptionRepository")
 */
class subscription
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
     * @ORM\Column(name="subscription_date", type="date")
     */
    private $subscriptionDate;


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
     * Set subscriptionDate
     *
     * @param \DateTime $subscriptionDate
     *
     * @return subscription
     */
    public function setSubscriptionDate($subscriptionDate)
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    /**
     * Get subscriptionDate
     *
     * @return \DateTime
     */
    public function getSubscriptionDate()
    {
        return $this->subscriptionDate;
    }
}

