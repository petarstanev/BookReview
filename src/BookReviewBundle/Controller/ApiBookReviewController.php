<?php
namespace BookReviewBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use BookReviewBundle\Entity\Book;
use BookReviewBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;



class ApiBookReviewController extends FOSRestController
{
    public function getReviewsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewBundle:Book')->find($slug);

        return $this->handleView($this->view($book->getReviews()));
    }

    public function getReviewAction($slug, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("BookReviewBundle:Review")->createQueryBuilder('r')
            ->where("r.book == :bookid")
            ->setParameter('bookid', '$id')
            ->getQuery()
            ->getResult();

        return $this->handleView($this->view($result));
    }
}
