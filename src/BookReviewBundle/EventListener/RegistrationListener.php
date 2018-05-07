<?php
/**
 * Created by PhpStorm.
 * User: stanev
 * Date: 07/05/2018
 * Time: 16:49
 */

namespace BookReviewBundle\EventListener;


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

            //exit;
        //return $jsonObj;
    }
}