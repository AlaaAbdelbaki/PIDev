<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * orders
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ordersRepository")
 */
class orders
{
    /**
     * @return ArrayCollection
     */
    public function getOrderLine()
    {
        return $this->order_line;
    }

    /**
     * @param ArrayCollection $order_line
     */
    public function setOrderLine($order_line)
    {
        $this->order_line = $order_line;
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
     * @ORM\OneToMany(targetEntity="order_line", mappedBy="orders" , orphanRemoval=true)
     */
    private $order_line;

    public function __construct()
    {
        $this->order_line = new ArrayCollection();
    }
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_date", type="date")
     */
    private $orderDate;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float")
     */
    private $total;


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
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return orders
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return orders
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }
}

