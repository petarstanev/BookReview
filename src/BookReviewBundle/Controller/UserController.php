<?php

namespace BookReviewBundle\Controller;

use BookReviewBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction()    {
        return $this->render('UserBundle:Default:index.html.twig');
    }

    public function checkoathAction(Request $request)
    {
        $currentUser = $this->getUser();
        $userid = $currentUser->getId();
        $em = $this->getDoctrine()->getManager();

        $em->getRepository('BookReviewBundle:AccessToken')->findOneBy(array('user_id' => $userid ));

        return $this->render('book/index.html.twig');
    }


    public function generateoathAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('BookReviewBundle\Form\UserType', $user);
        $form->handleRequest($request);

        //$currentUser = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {

            $grantRequest = new Request(array(
                'client_id'  => "1_11cy8sg0tga8ocgckssoog0cs0440884o8cg0sckwk40k8s40o",
                'client_secret' => "2mjdzx9jqaw4swkcwkcccscswcws408w4og848oscooo4osws0",
                'grant_type' => 'password',
                'username' => $user->getUsername(),
                'password' => $user->getPassword()
            ));

            try {
                $tokenResponse = $this->get('fos_oauth_server.server')->grantAccessToken($grantRequest);

                $token = $tokenResponse->getContent();
                $jsonObj = json_decode($token);
                $accessCode = $jsonObj->access_token;


                $currentUser = $this->getUser();
                $em = $this->getDoctrine()->getManager();

                $dbUser = $em->getRepository('BookReviewBundle:User')->findOneBy(array('username'=>$user->getUsername()));

                $dbUser->setAccesstokencode($accessCode);

                $em->flush();



                return $this->redirectToRoute('fos_user_profile_show');
            } catch (Exception $e) {
                return $this->render('user/oath.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('user/oath.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));


        /*
        $grantRequest = new Request(array(
            'client_id'  => "1_11cy8sg0tga8ocgckssoog0cs0440884o8cg0sckwk40k8s40o",
            'client_secret' => "2mjdzx9jqaw4swkcwkcccscswcws408w4og848oscooo4osws0",
            'grant_type' => 'password',
            'username' => $username,
            'password' => "$password"
        ));

        $tokenResponse = $this->get('fos_oauth_server.server')->grantAccessToken($grantRequest);

        $token = $tokenResponse->getContent();

        $jsonObj = json_decode($token);

        dump($jsonObj->access_token);
        exit;


        return $this->generateToken($user, 201);
        */
    }


}
