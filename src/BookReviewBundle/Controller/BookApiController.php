<?php

namespace BookReviewBundle\Controller;

use Beta\B;
use BookReviewBundle\Entity\Book;
use BookReviewBundle\Entity\GoogleBook;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookApiController extends Controller
{
    /**
     * Search book api.
     *
     * @Route("/{id}", name="book_show")
     */
    public function searchAction(Request $request)
    {
        $searchValueInput = $request->get('searchText');
        $searchValue = str_replace(' ', '+', $searchValueInput);
        dump($searchValueInput);

        $page = intval($request->get('p'));
        if ($page < 1)
            $page = 1;

        $books = array();
        $pages = array();
        $endPage =0;

        if ($searchValue != ''){
            $startIndex = ($page - 1 )* 10;

            $client = new \GuzzleHttp\Client();
            $uri = 'https://www.googleapis.com/books/v1/volumes?q='. $searchValue .'&startIndex='. $startIndex . '&projection=full&key=AIzaSyBS73RyaRRGdoFBLYdSSRGJPNGMigyggr8';

            #dump($uri);
            $res = $client->request('GET', $uri);
            $jsonObj = json_decode($res->getBody());

            for ($i = 0; $i < count($jsonObj->items); $i++){
                $book = new GoogleBook();
                $book->loadJsonData($jsonObj->items[$i]);
                $books[$i] = $book;
            }


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
        }

        return $this->render('BookReviewBundle:BookApi:index.html.twig', array(
            'books' => $books,
            'pages' => $pages,
            'selectedPage' => $page,
            'searchValue' => $searchValueInput,
            'endPage' => $endPage
        ));
    }

    public function detailsAction($id){
        $client = new \GuzzleHttp\Client();
        $uri = 'https://www.googleapis.com/books/v1/volumes/'. $id;

        #dump($uri);
        $res = $client->request('GET', $uri);
        $jsonObj = json_decode($res->getBody());
        $book = new GoogleBook();
        $book->loadJsonData($jsonObj);
        return $this->render('BookReviewBundle:BookApi:details.html.twig', array(
            'book' => $book
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
