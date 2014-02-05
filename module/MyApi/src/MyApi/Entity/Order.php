<?php
/**
 * Orders
 *
 */

namespace MyApi\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="orders")
 *
 * @author Gleb Metlov <glebm@mail.ru>
 */
class Order
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
    protected $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="\MyUser\Entity\User", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $firm_id;

    /**
     * @ORM\ManyToOne(targetEntity="\MyFirm\Entity\Firm", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="firm_id", referencedColumnName="id")
     */
    private $firm;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $client_id;

    /**
     * @ORM\ManyToOne(targetEntity="\MyClient\Entity\Client", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="order", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    private $jobs;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, unique=true)
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
    protected $amount;

    /**
     * @var int
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    protected $created;


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
     * Get user_id.
     *
     * @return int
     */
    public function getUser_Id()
    {
        return $this->user_id;
    }

    /**
     * Set user_id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setUser_Id($user_id)
    {
        $this->user_id = (int) $user_id;
    }

    /**
     * Get firm_id.
     *
     * @return int
     */
    public function getFirm_Id()
    {
        return $this->firm_id;
    }

    /**
     * Set firm_id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setFirm_Id($firm_id)
    {
        $this->firm_id = (int) $firm_id;
    }



    /**
     * Get firm.
     *
     * @return int
     */
    public function getFirm()
    {
        return $this->firm;
    }

    /**
     * Get firm.
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get client.
     *
     * @return int
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user.
     *
     * @param object $user
     *
     * @return void
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Set firm.
     *
     * @param object $firm
     *
     * @return void
     */
    public function setFirm($firm)
    {
        $this->firm = $firm;
    }

    /**
     * Set client.
     *
     * @param object $client
     *
     * @return void
     */
    public function setClient($client)
    {
        $this->client = $client;
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
     * Get amount.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amount.
     *
     * @param float $amount
     *
     * @return void
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
