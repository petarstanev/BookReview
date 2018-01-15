<?php

namespace BookReviewBundle\Controller;

use BookReviewBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Book controller.
 *
 * @Route("book")
 */
class BookController extends Controller
{
    /**
     * Lists all book entities.
     *
     * @Route("/", name="book_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $searchValue = $request->get('searchValue');

        if($searchValue != NULL) {
            $books= $em->getRepository("BookReviewBundle:Book")->createQueryBuilder('o')
                ->where("o.title LIKE :search OR o.author LIKE :search")
                ->setParameter('search', '%'.$searchValue.'%')
                ->getQuery()
                ->getResult();
        }
        else {
            $books = $em->getRepository('BookReviewBundle:Book')->findAll();
        }
        return $this->render('book/index.html.twig', array(
            'books' => $books,
        ));
    }

    /**
     * Search books by title and author
     *
     * @Method("Post")
     */
    public function searchAction($query ='')
    {
        $em = $this->getDoctrine()->getManager();

        $allBooks = $em->getRepository('BookReviewBundle:Book')->findAll();

        $foundBooks = array();

        foreach ($allBooks as $book) {
            if (strpos($book->getTitle(), $query) !== false) {
                $foundBooks = $book;
            }
        }

        return $this->render('book/index.html.twig', array(
            'books' => $foundBooks,
        ));
    }

    /**
     * Creates a new book entity.
     *
     * @Route("/new", name="book_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm('BookReviewBundle\Form\BookType', $book);
        $form->handleRequest($request);

        $currentUser = $this->getUser();
        $book->setUser($currentUser);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_show', array('id' => $book->getId()));
        }

        return $this->render('book/new.html.twig', array(
            'book' => $book,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a book entity.
     *
     * @Route("/{id}", name="book_show")
     * @Method("GET")
     */
    public function showAction(Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);

        return $this->render('book/show.html.twig', array(
            'book' => $book,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing book entity.
     *
     * @Route("/{id}/edit", name="book_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);
        $editForm = $this->createForm('BookReviewBundle\Form\BookType', $book);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_show', array('id' => $book->getId()));
        }

        return $this->render('book/edit.html.twig', array(
            'book' => $book,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a book entity.
     *
     * @Route("/{id}", name="book_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Book $book)
    {
        $form = $this->createDeleteForm($book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($book);
            $em->flush();
        }

        return $this->redirectToRoute('book_index');
    }

    /**
     * Creates a form to delete a book entity.
     *
     * @param Book $book The book entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Book $book)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('book_delete', array('id' => $book->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function findAction (Request $request) {
        $searchValue = $request->get('searchValue');

        $em = $this->getDoctrine()->getManager();

        $result = $em->getRepository("BookReviewBundle:Book")->createQueryBuilder('o')
            ->where("o.title LIKE :title")
            ->setParameter('title', '%'.$searchValue.'%')
            ->getQuery()
            ->getResult();

        $this->redirectToRoute('book_index');

        return $this->render('book/index.html.twig', array(
            'books' => $result,
        ));
    }
}
