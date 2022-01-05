<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\AppUserAuthenticator;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE = ['firstname','lastname','sexe','email','dateNaissance','createdAt','updateAt',];
    private $emailVerifier;
    private $userRepository;
    public function __construct(EmailVerifier $emailVerifier, UserRepository $userRepository)
    {
        $this->emailVerifier = $emailVerifier;
        $this->userRepository =$userRepository;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenGeneratorInterface $generateToken,TokenStorageInterface $storage,GuardAuthenticatorHandler $guardHandler, AppUserAuthenticator $authenticator): Response
    {
        //Validate token
        /*$token = $request->request->get("tokenadger");
        if (!$this->isCsrfTokenValid('tokenadger', $token))
        {
            return $this->render('indexpage/index.html.twig');
        }*/
        if($request->isMethod('POST')){
            $emailExist = $this->userRepository->findOneBy(['email' => $request->request->get('email')]);
            if(is_null($emailExist)) {
                $validator = Validation::createValidator();
                $input = $request->request->all();
                $groups = new Assert\GroupSequence(['Default','Custom']);
                $constraint = new Assert\Collection([
                    'email' => new Assert\Length(['min'=>10,'max'=>100]),
                    'firstname' => new Assert\Length(['min'=>5,'max'=>100]),
                    'lastname' => new Assert\Length(['min'=>5,'max'=>100]),
                    'datenaissance' => new Assert\NotBlank(),
                    'plainPassword' => new Assert\Length(['min'=>7,'max'=>100]),
                    'sexe' => new Assert\Length(['min'=>1]),
                    'tokenadger' => new Assert\Length(['min'=>1])
                ]);
                $violations = $validator->validate($input,$constraint,$groups);
                if(count($violations) !== 0){
                    //foreach $violation->getMessage() to get specify data error
                    $token = $storage->getToken('authenticate');
                    return $this->json( ['error'=> true,'message' => "Une ou plusieurs données obligatoires sont manquantes",'code'=> 400,'access_token' => $token ],Response::HTTP_CREATED ,[],[]);
                }

                $user = new User();
                $user
                    ->setEmail($request->request->get("email"))
                    ->setFirstname($request->request->get('firstname'))
                    ->setLastname($request->request->get('lastname'))
                    ->setDateNaissance(new \DateTime($request->request->get('datenaissance')))
                    ->setSexe($request->request->get('sexe'))
                    ->setPassword($passwordEncoder->encodePassword($user, $request->request->get("plainPassword")))
                    ->setCreatedAt(new \DateTimeImmutable());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // generate a signed url and email it to the user
                /*$this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('falitina04@gmail.com', 'SAYNA'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );*/
                // do anything else you need here, like send an email
                // Authenticate user after success register
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );


            }else{
                $token = $storage->getToken('authenticate');
                return $this->json( ['error'=> true,'message' => "Une ou plusieurs données sont erronées",'code'=> 409,'access_token' => $token ],Response::HTTP_CREATED ,[],[]);
            }
        }

        if ($this->getUser()) {
            $refresh = $generateToken->generateToken();
            $token = $storage->getToken('authenticate');
            return $this->json( ['error'=> false,'message' => "Actualisé",'user'=>$this->getUser(),'code'=>200,'access_token' => $token,'refresh'=> $refresh ],Response::HTTP_CREATED ,[],[
                'attributes' => self::ATTRIBUTES_TO_SERIALIZE
            ]);
        }else{
            return $this->render('indexpage/index.html.twig', [
            ]);
        }
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
