<?php
namespace MyUser\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An example entity that represents a role.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_role_linker")
 *
 * @author Gleb Metlov <glebm@mail.ru>
 */
class UserRoleLinker
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $user_id;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $role_id;

    /**
     * Get the id.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setUserId($id)
    {
        $this->user_id = (int)$id;
    }

    /**
     * Get the role id.
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * Set the role id.
     *
     * @param int $roleId
     *
     * @return void
     */
    public function setRoleId($roleId)
    {
        $this->role_id = (int) $roleId;
    }

}
