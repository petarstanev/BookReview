<?php

namespace BookReviewBundle\Controller;

use BookReviewBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class BookApiController extends Controller
{
    /**
     * Search book api.
     *
     * @Route("/{id}", name="book_show")
     */
    public function searchAction(Request $request)
    {
        $searchValueInput = $request->get('value');
        $searchValue = str_replace(' ', '+', $searchValueInput);

        $page = intval($request->get('page'));

        dump($page);
        $startIndex = ($page - 1 )* 10;

        $client = new \GuzzleHttp\Client();
        $uri = 'https://www.googleapis.com/books/v1/volumes?q='. $searchValue .'&startIndex='. $startIndex . '&projection=full&key=AIzaSyBS73RyaRRGdoFBLYdSSRGJPNGMigyggr8';

        #dump($uri);
        $res = $client->request('GET', $uri);
        $jsonObj = json_decode($res->getBody());

        $books = array();

        for ($i = 0; $i < count($jsonObj->items); $i++){
            $jsonResult = $jsonObj->items[$i]->volumeInfo;
            $book = new Book();
            $book->loadJsonData($jsonResult);
            $books[$i] = $book;
        }


        $pages = array();

        $numberPages = $jsonObj->totalItems/10;

        $startingPage = $page - 5;

        if ($startingPage < 1){
            $startingPage = 1;
        }

        $endPage = $startingPage+10;
        if ($endPage > $numberPages){
            $endPage = $numberPages;
        }

        for ($i = $startingPage; $i <= $endPage; $i++){
            $pages[$i] = $i;
        }



        return $this->render('BookReviewBundle:BookApi:index.html.twig', array(
            'books' => $books,
            'pages' => $pages,
            'selectedPage' => $page,
            'searchValue' => $searchValueInput,
            'endPage' => $endPage
        ));
    }

    /**
     * @Route("/event")
     */
    public function eventAction()
    {
        return $this->render('BookReviewBundle:BookApi:event.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/talks")
     */
    public function talksAction()
    {
        #http://api.postcodes.io/random/postcodes

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'https://api.postcodes.io/random/postcodes');


        $jsonObj = json_decode($res->getBody());
        $firstName = $jsonObj->result->postcode;

        $a = new Book();
        $a->setTitle($firstName);
        return $this->render('BookReviewBundle:BookApi:talks.html.twig', array(
            'book' => $a
        ));
    }

}
