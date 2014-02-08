<?php
/**
 * Jobs
 *
 */

namespace MyApi\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 *
 * @author Gleb Metlov <glebm@mail.ru>
 */
class Job
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var type
     * @ORM\Column(type="integer")
     */
    protected $order_id;

    /**
     * @ORM\ManyToOne(targetEntity="\MyApi\Entity\Order", cascade={"persist"}, fetch="EAGER", inversedBy="jobs")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $order;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $device_id;

    /**
     * @ORM\ManyToOne(targetEntity="\MyDevice\Entity\Device", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     */
    private $device;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true, options={"collate"="utf8_general_ci"})
     */
    protected $comment;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" = 1})
     */
    protected $state = 1;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected $price;

    /**
     * @var int
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    protected $created;

    /**
     * @var int
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Initialies
     */
    public function __construct()
    {

    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Get order_id.
     *
     * @return int
     */
    public function getOrder_Id()
    {
        return $this->order_id;
    }

    /**
     * Set order_id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setOrder_Id($order_id)
    {
        $this->order_id = (int) $order_id;
    }

    /**
     * Get device_id.
     *
     * @return int
     */
    public function getDevice_Id()
    {
        return $this->device_id;
    }

    /**
     * Set device_id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setDevice_Id($device_id)
    {
        $this->device_id = (int) $device_id;
    }



    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Get device.
     *
     * @return int
     */
    public function getDevice()
    {
        return $this->device;
    }


    /**
     * Set order.
     *
     * @param object $order
     *
     * @return void
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Set device.
     *
     * @param object $device
     *
     * @return void
     */
    public function setDevice($device)
    {
        $this->device = $device;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get comment.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set comment.
     *
     * @param string $email
     *
     * @return void
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price.
     *
     * @param float $price
     *
     * @return void
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get created.
     *
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get updated.
     *
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set updated.
     *
     * @param string $updated
     *
     * @return void
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }


    /**
     * Helper function.
     */
    public function exchangeArray($data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = ($val !== null) ? $val : null;
            }
        }
    }

    /**
     * Helper function
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function __get($property) {
        return $this->$property;
    }

    public function __set($property, $value) {
        $this->$property = $value;
    }

}
