<?php
namespace BookReviewBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use BookReviewBundle\Entity\Book;
use BookReviewBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;


class ApiBookController extends FOSRestController
{
    public function getBooksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('BookReviewBundle:Book')->findAll();
        return $this->handleView($this->view($books));
    }
}
