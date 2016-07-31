<?php
// src/AppBundle/Entity/User.php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    // Ajout d'un champ dateOfBirth
    /**
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank(message="Please enter your date of birth.", groups={"Registration", "Profile"})
     * @Assert\DateTime(message="This value is not a valid datetime", groups={"Registration", "Profile"})
     * @Assert\LessThan("-18 years UTC", message="This site is reserved for 18+")
     * 
     */
    protected $dateOfBirth;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Gets the date of birth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTime $date = null)
    {
        $this->dateOfBirth = $date;

        return $this;
    }
}