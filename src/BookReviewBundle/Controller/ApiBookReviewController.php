<?php
namespace BookReviewBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use BookReviewBundle\Entity\Book;
use BookReviewBundle\Entity\Review;
use BookReviewBundle\Form\ReviewChildApiType;
use Symfony\Component\HttpFoundation\Request;



class ApiBookReviewController extends FOSRestController
{
    public function getReviewsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('BookReviewBundle:Book')->find($slug);

        return $this->handleView($this->view($book->getReviews()));
    }

    public function getReviewAction($slug, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository("BookReviewBundle:Review")->createQueryBuilder('r')
            ->where("r.book == :bookid")
            ->setParameter('bookid', '$id')
            ->getQuery()
            ->getResult();

        return $this->handleView($this->view($result));
    }

    public function postReviewAction($slug, Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewChildApiType::class, $review);
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
            $book = $em->getRepository('BookReviewBundle:Book')->find($slug);//get book from id
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

}
