<?php

namespace BookReviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="BookReviewBundle\Repository\UserRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /** @ORM\Column(name="access_token", type="string", length=255, nullable=true) */
    protected $access_token;

    /**
     * @ORM\OneToMany(targetEntity="BookReviewBundle\Entity\Review", mappedBy="user")
     *
     *
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
        parent::__construct();
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

    public function setGithubId($githubId) {
        $this->github_id = $githubId;

        return $this;
    }

    public function getGithubId() {
        return $this->github_id;
    }

    public function setGithubAccessToken($githubAccessToken) {
        $this->github_access_token = $githubAccessToken;

        return $this;
    }

    public function getGithubAccessToken() {
        return $this->github_access_token;
    }
}

