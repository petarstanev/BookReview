<?php
/**
 * Created by PhpStorm.
 * User: stanev
 * Date: 07/05/2018
 * Time: 16:19
 */

namespace BookReviewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserOATHController extends Controller
{

    public function indexAction()
    {
        $a = AccessTokenRepository();




        /*$grantRequest = new Request(array(
            'client_id'  => "1_11cy8sg0tga8ocgckssoog0cs0440884o8cg0sckwk40k8s40o",
            'client_secret' => "2mjdzx9jqaw4swkcwkcccscswcws408w4og848oscooo4osws0",
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password
        ));

        $tokenResponse = $this->get('fos_oauth_server.server')->grantAccessToken($grantRequest);

        $token = $tokenResponse->getContent();
        */

        /*$tokenManager = $this->container->get('fos_oauth_server.access_token_manager.default');
        $accessToken = $tokenManager->findTokenByToken(
            $a = $this->container->get('security.authorization_checker')
        );
        $client = $accessToken->getClient();*/

        $user = $this->get('security.token_storage')->getToken()->getUser(); // WORKING !!!


        //$client = $accessToken->getClient();

        dump($user);
        exit;

        return $this->render('oath/index.html.twig', array(
            'authcode' => $client,
        ));
    }
}