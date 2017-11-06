<?php

namespace BookReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BookReviewBundle:Default:index.html.twig');
    }
}
