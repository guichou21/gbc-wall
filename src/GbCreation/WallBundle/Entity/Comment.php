<?php

namespace GbCreation\WallBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
* @ORM\Entity(repositoryClass="GbCreation\WallBundle\Repository\CommentRepository")
* @ORM\Table(name="gbc_wall_comment")
* @ORM\HasLifecycleCallbacks()
*/
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @ORM\Column(type="boolean", name="is_approved")
     */
    protected $isApproved;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="comments")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $idItem;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    protected $updated;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setUpdated(new \DateTime());

        $this->setIsApproved(true);
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
        {
            $metadata->addPropertyConstraint('author', new NotBlank(array(
                'message' => 'Vous devez renseigner un nom'
            )));
            $metadata->addPropertyConstraint('comment', new NotBlank(array(
                'message' => 'Vous devez renseigner un commentaire'
            )));
        }


    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue()
    {
       $this->setUpdated(new \DateTime());
    }

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
     * Set author
     *
     * @param string $author
     * @return Comment
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Comment
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set idItem
     *
     * @param \GbCreation\WallBundle\Entity\Item $idItem
     * @return Comment
     */
    public function setIdItem(\GbCreation\WallBundle\Entity\Item $idItem = null)
    {
        $this->idItem = $idItem;
    
        return $this;
    }

    /**
     * Get idItem
     *
     * @return \GbCreation\WallBundle\Entity\Item 
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * Set isApproved
     *
     * @param boolean $isApproved
     * @return Comment
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    
        return $this;
    }

    /**
     * Get isApproved
     *
     * @return boolean 
     */
    public function getIsApproved()
    {
        return $this->isApproved;
    }
}