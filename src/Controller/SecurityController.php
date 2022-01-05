<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE = ['firstname','lastname','sexe','email','dateNaissance','createdAt','updateAt',];

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils,  Request $request,TokenStorageInterface $storage): Response
    {
         if ($this->getUser()) {
             $token = $storage->getToken('authenticate');
             return $this->json( ['error'=> false,'message' => "L'utilisateur a été authentiﬁé",'user'=>$this->getUser(),'access_token' => $token ],Response::HTTP_CREATED ,[],[
                 'attributes' => self::ATTRIBUTES_TO_SERIALIZE
             ]);
         }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();
        if($error==null){
             return $this->render('indexpage/index.html.twig', [
            ]);
        }
        return $this->json([
            'error' => true,
            'data' => $error->getMessageData(),
            'message' => $error->getMessageKey(),
            'code' => $error->getCode(),
        ], Response::HTTP_CREATED,[],[]);
    }
}
