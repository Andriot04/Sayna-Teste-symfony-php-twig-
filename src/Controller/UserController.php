<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use Inacho\CreditCard;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;


class UserController extends AbstractController
{
    public function __construct(UserRepository $userRepository,TokenStorageInterface $storage, CartRepository $cartRepository)
    {
        $this->userRepository = $userRepository;
        $this->storage = $storage;
        $this->cartRepository = $cartRepository;
    }
    /**
     * @Route("/user", name="update_user")
     */
    public function updateUser(Request $request): Response
    {
        $emailExist = $this->userRepository->findOneBy(['email' => $request->request->get('email')]);
        if(is_null($emailExist) || $emailExist == $this->getUser()->getUsername()) {
            $validator = Validation::createValidator();
            $input = $request->request->all();
            $groups = new Assert\GroupSequence(['Default','Custom']);
            $constraint = new Assert\Collection([
                'firstnamemodif' => new Assert\Length(['min'=>5,'max'=>100]),
                'lastnamemodif' => new Assert\Length(['min'=>5,'max'=>100]),
                'datenaissancemodif' => new Assert\NotBlank(),
                'sexemodif' => new Assert\Length(['min'=>1]),
                'tokenadger' => new Assert\Length(['min'=>1])
            ]);
            $violations = $validator->validate($input,$constraint,$groups);
            $token = $this->storage->getToken('authenticate');
            if(empty($token)){
                return $this->json( ['error'=> true,'message' => "Votre token n'est pas correct",'code'=> 401 ],Response::HTTP_CREATED ,[],[]);
            }
            if(count($violations) !== 0){
                return $this->json( ['error'=> true,'message' => "Une ou plusieurs données sont éronnées",'code'=> 400,'access_token' => $token ],Response::HTTP_CREATED ,[],[]);
            }
            $user = $this->getUser();
            $user
                ->setFirstname($request->request->get('firstnamemodif'))
                ->setLastname($request->request->get('lastnamemodif'))
                ->setDateNaissance(new \DateTime($request->request->get('datenaissancemodif')))
                ->setSexe($request->request->get('sexemodif'))
                ->setUpdateAt(new \DateTimeImmutable());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse([
                'error'=> false,'message' => "Vos données ont été mises à jour",
            ]);
        }else{
            return $this->json( ['error'=> true,'message' => "Une ou plusieurs données sont éronnées",'code'=> 400 ],Response::HTTP_CREATED ,[],[]);
        }

    }

    /**
     * @Route("/user/off", name="app_logout")
     */
    public function logout(TokenStorageInterface $tokenStorage)
    {
        try {
            $tokenStorage->getToken('authenticate');
            session_destroy();
            return $this->json( ['error'=> false,'message' => "L'utilisateur a été déconnecté avec succès",'code'=>200],Response::HTTP_CREATED ,[],[]);
        }catch (\Exception $e){
            return $this->json( ['error'=> true,'message' => "Votre token n'est pas correct",'code'=>401],Response::HTTP_CREATED ,[],[]);
        }
    }

    /**
     * @Route("/user/cart", name="add_card")
     */
    public function newCard(Request $request,Security $security): Response
    {
        if($this->isGranted('ROLE_USER')){
        $validator = Validation::createValidator();
        $input = $request->request->all();
        $groups = new Assert\GroupSequence(['Default','Custom']);
        $constraint = new Assert\Collection([
            'cartNumber' => new Assert\Length(['min'=>10,'max'=>30]),
            'year' => new Assert\Length(['min'=>4]),
            'month' => new Assert\Length(['min'=>1]),
            'tokenadger' => new Assert\NotBlank()
        ]);
        $violations = $validator->validate($input,$constraint,$groups);
        $token = $this->storage->getToken('authenticate');
        $cardExist = $this->cartRepository->findOneBy(['cardnumber'=> $request->request->get('cartNumber')]);
        if(!empty($token) && $this->isCsrfTokenValid('tokenadger',$request->request->get('tokenadger') )) {
                if (empty($cardExist)) {
                    if (count($violations) == 0) {
                        $card = CreditCard::validCreditCard($request->request->get("cartNumber"));
                        $validDate = CreditCard::validDate($request->request->get("year"), $request->request->get("month"));
                        if ($card['valid'] == false || $validDate == false) {
                            return $this->json(['error' => true, 'message' => "Informations bancaire incorrectes", 'code' => 402], Response::HTTP_CREATED, [], []);
                        } else {
                            $newcard = new Cart();
                            $newcard
                                ->setCardnumber($request->request->get("cartNumber"))
                                ->setYear($request->request->get("year"))
                                ->setMonth($request->request->get("month"))
                                ->setUser($this->getUser())
                                ->setTypecarte($card['type']);

                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($newcard);
                            $entityManager->flush();
                            return $this->json(['error' => false, 'message' => "Vos données ont été mises à jour", 'code' => 200], Response::HTTP_CREATED, [], []);
                        };
                    } else {
                        return $this->json(['error' => $violations, 'message' => "Une ou plusieurs données sont éronnées", 'code' => 409, 'access_token' => $token], Response::HTTP_CREATED, [], []);
                    }
                } else {
                    return $this->json(['error' => true, 'message' => "La carte existe déjà", 'code' => 409], Response::HTTP_CREATED, [], []);
                }
            }
        else{
            return $this->json( ['error'=> true,'message' => "Votre token n'est pas correct",'code'=> 401 ],Response::HTTP_CREATED ,[],[]);
        }

        }else{
            return $this->json( ['error'=> true,'message' => "Vos droits d'accès ne permettent pas d'accéder à la ressource",'code'=> 403 ],Response::HTTP_CREATED ,[],[]);
        }
    }

    /**
     * @Route("/subscription", name="subscription")
     */
    public function subscriptions(Request $request, CartRepository $cartRepository){

        $duréeEssai = '5M'; //5 minute
        $dureeQuelconque1 = (60*24).'M'; //1 jour
        $dureeQuelconque2 = (60*24*30).'M'; //1 mois
        if($this->isGranted('ROLE_USER')){
            $token = $this->storage->getToken('authenticate');
            if(!empty($token) && $this->isCsrfTokenValid('tokenadger',$request->request->get('tokenadger') )) {
                $infoCard = $cartRepository->findOneBy(['cardnumber' => $request->request->get("idcard")]);
                $card = CreditCard::validCreditCard($request->request->get("idcard"));
                $validDate = CreditCard::validCvc($request->request->get('cvc'),$infoCard->getTypecarte());
                if ($card['valid'] == false || $validDate == false) {
                    return $this->json(['error' => true, 'message' => "Une ou plusieurs données obligatoire sont manquantes", 'code' => 402], Response::HTTP_CREATED, [], []);
                } else {
                    $entityManager = $this->getDoctrine()->getManager();
                    if(!$this->getUser()->getFirstSubscriotionTrial()){
                        //premier abonnement
                        $user = $this->getUser();
                        $fiveminute = new \DateTimeImmutable();
                        $fiveminute =$fiveminute->add(new \DateInterval('PT'.$duréeEssai));
                        $user->setFinabonnement($fiveminute);
                        $user->setFirstSubscriotionTrial(true);
                        $entityManager->persist($user);
                        $entityManager->flush();
                        return $this->json(['error' => false, 'message' => "Votre période d'essai vient d'être activé(5min)", 'code' => 200], Response::HTTP_CREATED, [], []);
                    }
                        //abonnement quelconque
                        //payement avec API STRIP Symfony
                          //cart number , cvc
                          //return true for the test
                        //
                        $user = $this->getUser();
                        $fiveminute = new \DateTimeImmutable();
                        $fiveminute =$fiveminute->add(new \DateInterval('PT'.$dureeQuelconque1));
                        $user->setFinabonnement($fiveminute);
                        $entityManager->persist($user);
                        $entityManager->flush();
                    return $this->json(['error' => false, 'message' => "Votre abonnement a bien été mise à jour", 'code' => 200], Response::HTTP_CREATED, [], []);
                }
            }
            else {
                return $this->json(['error' => true, 'message' => "Votre token n'est pas correct", 'code' => 401], Response::HTTP_CREATED, [], []);
            }
            }
        else{
            return $this->json( ['error'=> true,'message' => "Vos droits d'accès ne permettent pas d'accéder à la ressource",'code'=> 403 ],Response::HTTP_CREATED ,[],[]);
        }
    }
    /**
     * @Route("/abonnement", name="abonnement")
     */
    public function expireds(){
        if ($this->getUser()->getFinabonnement() < new \DateTimeImmutable()) {
            return $this->json(['error' => true, 'message' => "votre période d'essai ou abonnement est terminé"], Response::HTTP_CREATED, [], []);
        }
        return $this->json(['error' => false]);
    }
    /**
     * @Route("/user/{id}",name="suppression", methods={"GET"} )
     */
    public function suppressionUser($id,UserRepository $user, CartRepository $card, TokenStorageInterface $storage){
        try {
            $token = $storage->getToken('authenticate');
            if ($token) {
                $em = $this->getDoctrine()->getManager();
                if ($this->getUser()) {
                    $users = $user->find($id);
                    $cards = $card->findBy(['user'=>$id]);
                    foreach ($cards as $cards){
                        $em->remove($cards);
                    }
                    if (!is_null($users)) {
                        session_destroy();
                        $em->remove($users);
                        $em->flush();
                        return new JsonResponse(['error'=> true,'message'=>"Votre compte et le compte de vos enfants ont été supprimés avec succès", 'code'=>200]);
                    }
                }
            }else{
                return new JsonResponse(['error'=> true,'message'=>"Votre token n'est pas correct", 'code'=>401]);
            }
        }catch (\Exception $e){
        return new JsonResponse(['error' => true, 'message' => "Le compte courant n'est plus valide", 'code' => 500]);
        }
    }
}
