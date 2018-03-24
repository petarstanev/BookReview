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

    public function getUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('BookReviewBundle:User')->find($id);
        if(!$user) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($user);
        }
        return $this->handleView($view);
    }
}
