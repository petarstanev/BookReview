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
        $searchValue = $request->get('value');
        $searchValue = str_replace(' ', '+', $searchValue);

        $page = intval($request->get('page'));

        dump($page);

        $client = new \GuzzleHttp\Client();

        if ($page == 1){
            $uri = 'https://www.googleapis.com/books/v1/volumes?q='. $searchValue .'&projection=full&key=AIzaSyBS73RyaRRGdoFBLYdSSRGJPNGMigyggr8';
        }else{

            $startIndex = ($page - 1 )* 10;
            $uri = 'https://www.googleapis.com/books/v1/volumes?q='. $searchValue .'&startIndex='. $startIndex . '&projection=full&key=AIzaSyBS73RyaRRGdoFBLYdSSRGJPNGMigyggr8';
        }
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




        return $this->render('BookReviewBundle:BookApi:index.html.twig', array(
            'books' => $books
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
