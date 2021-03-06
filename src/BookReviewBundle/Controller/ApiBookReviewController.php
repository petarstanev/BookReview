<?php
namespace BookReviewBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use BookReviewBundle\Entity\Book;
use BookReviewBundle\Entity\Review;
use BookReviewBundle\Form\ReviewChildApiType;
use Symfony\Component\HttpFoundation\Request;



class ApiBookReviewController extends FOSRestController
{
    public function getReviewsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewBundle:Book')->find($slug);

        if(!$book) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($book->getReviews());
        }

        return $this->handleView($view);
    }

    public function getReviewAction($slug, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository('BookReviewBundle:Review')->findBy(array('id' => $id, 'book' => $slug));

        if(!$result) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($result);
        }

        return $this->handleView($view);
    }
}
