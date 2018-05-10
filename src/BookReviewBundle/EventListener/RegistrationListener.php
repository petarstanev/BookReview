<?php
/**
 * Created by PhpStorm.
 * User: stanev
 * Date: 10/05/2018
 * Time: 11:12
 */

namespace BookReviewBundle\EventListener;

use BookReviewBundle\Controller\UserController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'fos_user.registration.success'  => array('onRegistrationSuccess', 10),
        );
    }
    public function onRegistrationSuccess(FormEvent $event)
    {
        /*$user = $event->getForm()->getData();


        $grantRequest = new Request(array(
            'client_id'  => "1_11cy8sg0tga8ocgckssoog0cs0440884o8cg0sckwk40k8s40o",
            'client_secret' => "2mjdzx9jqaw4swkcwkcccscswcws408w4og848oscooo4osws0",
            'grant_type' => 'password',
            'username' => $user->getUsername(),
            'password' => $user->getPlainPassword()
        ));


        $yourController = $this->get('useroath');

        $yourController->generateoathAction();

        $tokenResponse = $this->get('fos_oauth_server.server')->grantAccessToken($grantRequest);

        $token = $tokenResponse->getContent();

        $jsonObj = json_decode($token);

        dump($jsonObj->access_token);
        exit;



        $userController = new UserController();
        $userController->generateoathAction($user->getUsername(),$user->getPlainPassword());



        //$tokenResponse = $this->getSubscribedEvents('fos_oauth_server.server');

        //$token = $tokenResponse->getContent();

        $token  = $this->getSubscribedEvents('fos_oauth_server.controller.token')->tokenAction($grantRequest);

        $jsonObj = json_decode($token->getBody());

        //return $jsonObj->id;

        dump($jsonObj);
        exit;

        //exit;
        //return $jsonObj;*/
    }
}