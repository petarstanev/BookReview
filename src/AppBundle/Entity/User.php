<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="BookReviewBundle\Entity\Review", mappedBy="user")
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="BookReviewBundle\Entity\Book", mappedBy="user")
     */
    private $books;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->books = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param mixed $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * @return mixed
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param mixed $books
     */
    public function setBooks($books)
    {
        $this->books = $books;
    }


}