<?php
/**
 * Clients
 *
 */

namespace MyClient\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="client")
 *
 * @author Gleb Metlov <glebm@mail.ru>
 */
class Client
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $firm_id;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $address;

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
    public function getUserId()
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
    public function setUserId($user_id)
    {
        $this->user_id = (int) $user_id;
    }

    /**
     * Get firm_id.
     *
     * @return int
     */
    public function getFirmId()
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
    public function setFirmId($firm_id)
    {
        $this->firm_id = (int) $firm_id;
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
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone.
     *
     * @param string $email
     *
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set address.
     *
     * @param string $email
     *
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
}
