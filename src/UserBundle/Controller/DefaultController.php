<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();
        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    public function promoteUser($user_id = 0)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $user_id));

        $userManager = $this->get('fos_user.user_manager');
        $user->addRole('ROLE_MODERATOR');
        $userManager->updateUser($user);
    }

    public function demoteUser($user_id = 0)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $user_id));

        $userManager = $this->get('fos_user.user_manager');
        $user->removeRole('ROLE_MODERATOR');
        $userManager->updateUser($user);
    }
}
