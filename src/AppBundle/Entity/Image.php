<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $ownerId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "image/gif", "image/jpeg", "image/pjpeg", "image/png" })
     * @ORM\Column(name="path", type="string", length=255, unique=true)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploaded_at", type="datetime")
     */
    private $uploadedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=255)
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="participated_votes", type="integer", nullable=true)
     */
    private $participatedVotes;

    /**
     * @var integer
     *
     * @ORM\Column(name="won_votes", type="integer", nullable=true)
     */
    private $wonVotes;

    /**
     * @var float
     *
     * @ORM\Column(name="votes_ratio", type="float", nullable=true)
     */
    private $votesRatio;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

/**********************************
* ownerId
*/
    /**
     * Get ownerId
     *
     * @return integer 
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set ownerId
     *
     * @param integer $ownerId
     * @return Image
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }
/*****************************************
*/

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set uploadedAt
     *
     * @param \DateTime $uploadedAt
     * @return Image
     */
    public function setUploadedAt($uploadedAt)
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }

    /**
     * Get uploadedAt
     *
     * @return \DateTime 
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Image
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

/*****************************************/
    /**
     * Get participatedVotes
     *
     * @return integer 
     */
    public function getParticipatedVotes()
    {
        return $this->participatedVotes;
    }

    /**
     * Set participatedVote
     *
     * @param integer $participatedVotes
     * @return Image
     */
    public function setParticipatedVotes($participatedVotes)
    {
        $this->participatedVotes = $participatedVotes;

        return $this;
    }

/****** ******/
    /**
     * Get wonVotes
     *
     * @return integer 
     */
    public function getWonVotes()
    {
        return $this->wonVotes;
    }

    /**
     * Set wonVotes
     *
     * @param integer $wonVotes
     * @return Image
     */
    public function setWonVotes($wonVotes)
    {
        $this->wonVotes = $wonVotes;

        return $this;
    }

/****** ******/
    /**
     * Get votesRatio
     *
     * @return float 
     */
    public function getVotesRatio()
    {
        return $this->votesRatio;
    }

    /**
     * Set votesRatio
     *
     * @param float $votesRatio
     * @return Image
     */
    public function setVotesRatio($votesRatio)
    {
        $this->votesRatio = $votesRatio;

        return $this;
    }    
/*****************************************/

}
