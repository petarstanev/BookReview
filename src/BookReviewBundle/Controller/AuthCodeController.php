<?php

namespace BookReviewBundle\Controller;

use BookReviewBundle\Entity\AuthCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Authcode controller.
 *
 * @Route("authcode")
 */
class AuthCodeController extends Controller
{
    /**
     * Lists all authCode entities.
     *
     * @Route("/", name="authcode_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $authCodes = $em->getRepository('BookReviewBundle:AuthCode')->findAll();

        return $this->render('authcode/index.html.twig', array(
            'authCodes' => $authCodes,
        ));
    }

    /**
     * Creates a new authCode entity.
     *
     * @Route("/new", name="authcode_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $authCode = new Authcode();
        $form = $this->createForm('BookReviewBundle\Form\AuthCodeType', $authCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($authCode);
            $em->flush();

            return $this->redirectToRoute('authcode_show', array('id' => $authCode->getId()));
        }

        return $this->render('authcode/new.html.twig', array(
            'authCode' => $authCode,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a authCode entity.
     *
     * @Route("/{id}", name="authcode_show")
     * @Method("GET")
     */
    public function showAction(AuthCode $authCode)
    {
        $deleteForm = $this->createDeleteForm($authCode);

        return $this->render('authcode/show.html.twig', array(
            'authCode' => $authCode,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing authCode entity.
     *
     * @Route("/{id}/edit", name="authcode_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AuthCode $authCode)
    {
        $deleteForm = $this->createDeleteForm($authCode);
        $editForm = $this->createForm('BookReviewBundle\Form\AuthCodeType', $authCode);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('authcode_edit', array('id' => $authCode->getId()));
        }

        return $this->render('authcode/edit.html.twig', array(
            'authCode' => $authCode,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a authCode entity.
     *
     * @Route("/{id}", name="authcode_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AuthCode $authCode)
    {
        $form = $this->createDeleteForm($authCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($authCode);
            $em->flush();
        }

        return $this->redirectToRoute('authcode_index');
    }

    /**
     * Creates a form to delete a authCode entity.
     *
     * @param AuthCode $authCode The authCode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AuthCode $authCode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('authcode_delete', array('id' => $authCode->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
