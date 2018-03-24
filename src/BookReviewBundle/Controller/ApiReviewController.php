<?php
namespace BookReviewBundle\Controller;

use BookReviewBundle\Entity\Review;
use BookReviewBundle\Form\ReviewApiType;
use BookReviewBundle\Form\ReviewType;
use FOS\RestBundle\Controller\FOSRestController;

use BookReviewBundle\Entity\Book;
use BookReviewBundle\Form\BookType;
use Symfony\Component\HttpFoundation\Request;


class ApiReviewController extends FOSRestController
{
    public function getReviewsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reviews = $em->getRepository('BookReviewBundle:Review')->findAll();
        return $this->handleView($this->view($reviews));
    }

    public function getReviewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BookReviewBundle:Review')->find($id);
        if(!$review) {
            $view = $this->view(null, 404);
        } else {
            $view = $this->view($review);
        }
        return $this->handleView($view);
    }

    public function postReviewAction(Request $request)
    {
        // prepare the form and remove the submit button
        $review = new Review();
        $form = $this->createForm(ReviewApiType::class, $review);

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
            $book = $em->getRepository('BookReviewBundle:Book')->find($review->getBook());//get book from id
            $review->setBook($book);
            $review->setCreatedDate(new \DateTime());
            $em->persist($review);
            $em->flush();
            // set status code to 201 and set the Location header
            // to the URL to retrieve the blog entry - Point 5
            return $this->handleView($this->view(null, 201)
                ->setLocation(
                    $this->generateUrl('review_show',
                        ['id' => $review->getId()]
                    )
                )
            );
        } else {
        // the form isn't valid so return the form
        // along with a 400 status code
            $errors = $form->getErrors();
            $data = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ];
            return $this->handleView($this->view($data, 400));
        }
    }

    public function putReviewAction($id, Request $request)
    {
        // prepare the form and remove the submit button
        $review = $this->getDoctrine()->getRepository("BookReviewBundle:Review")->find($id);

        $form = $this->createForm(ReviewType::class, $review);
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
            return $this->handleView($this->view(null, 200)
                ->setLocation(
                    $this->generateUrl('review_show',
                        ['id' => $review->getId()]
                    )
                )
            );
        } else {
            $errors = $form->getErrors();
            $data = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ];
            return $this->handleView($this->view($data, 400));
        }
    }

    public function deleteReviewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository('BookReviewBundle:Review')->find($id);
        if(!$review) {
            $view = $this->view(null, 404);
        } else {
            $em->remove($review);
            $em->flush();
            $view = $this->view(null, 200);
        }
        return $this->handleView($view);
    }
}
