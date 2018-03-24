<?php
namespace BookReviewBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use BookReviewBundle\Entity\Book;
use BookReviewBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;


class ApiUserBookController extends FOSRestController
{
    public function getBooksAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('BookReviewBundle:Book')->findBy(array('user' => $slug));

        if(!$result) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($result);
        }

        return $this->handleView($view);
    }

    public function getBookAction($slug, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository('BookReviewBundle:Book')->findBy(array('id' => $id, 'user' => $slug));

        if(!$result) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($result);
        }

        return $this->handleView($view);
    }
}
