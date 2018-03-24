<?php
namespace BookReviewBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use BookReviewBundle\Entity\Book;
use BookReviewBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;


class ApiUserController extends FOSRestController
{
    public function getUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('BookReviewBundle:User')->findAll();
        return $this->handleView($this->view($books));
    }

}
