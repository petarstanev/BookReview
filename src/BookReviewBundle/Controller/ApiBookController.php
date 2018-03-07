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

    public function getBookAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewBundle:Book')->find($id);
        if(!$book) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($book);
        }
        return $this->handleView($view);
    }

    public function postBookAction(Request $request)
    {
        // prepare the form and remove the submit button
        $bookEntry = new Book();
        $form = $this->createForm(BookType::class, $bookEntry);
        // Point 1 of list above
        if($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }
        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));
        // Point 2 of list above
        if($form->isValid()) {
            // Point 4 of list above
            $em = $this->getDoctrine()->getManager();

            //$bookEntry->setAuthor($this->getUser());

            $em->persist($bookEntry);
            $em->flush();
            // set status code to 201 and set the Location header
            // to the URL to retrieve the blog entry - Point 5
            return $this->handleView($this->view(null, 201)
                ->setLocation(
                    $this->generateUrl('book_show',
                        ['id' => $bookEntry->getId()]
                    )
                )
            );
        } else {
        // the form isn't valid so return the form
        // along with a 400 status code
            return $this->handleView($form->getErrorsAsString(), 400);
        }

    }

    public function putBookAction($id, Request $request)
    {
        // prepare the form and remove the submit button
        $bookEntry = $this->getDoctrine()->getRepository("BookReviewBundle:Book")->find($id);

        $form = $this->createForm(BookType::class, $bookEntry);
        // Point 1 of list above
        if($request->getContentType() != 'json') {
            return $this->handleView($this->view(null, 400));
        }
        // json_decode the request content and pass it to the form
        $form->submit(json_decode($request->getContent(), true));
        // Point 2 of list above
        if($form->isValid()) {
            // Point 4 of list above
            $em = $this->getDoctrine()->getManager();


            $em->flush();
            // set status code to 201 and set the Location header
            // to the URL to retrieve the blog entry - Point 5
            return $this->handleView($this->view(null, 201)
                ->setLocation(
                    $this->generateUrl('book_show',
                        ['id' => $bookEntry->getId()]
                    )
                )
            );
        } else {
            // the form isn't valid so return the form
            // along with a 400 status code
            return $this->handleView($form->getErrors(true), 400);
        }

    }
}
