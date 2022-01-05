<?php

namespace App\Security;

use App\Entity\TentativeLogin;
use App\Entity\User;
use App\Repository\TentativeLoginRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppUserAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $tentiveloginRepository;

    public function __construct(EntityManagerInterface $entityManager,TokenStorageInterface $storToken, TentativeLoginRepository $tentativeLoginRepository, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->tentiveloginRepository = $tentativeLoginRepository;
        $this->tokenStorage = $storToken;

    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        if($request->request->get('email') == null || $request->request->get('password')== null){
            throw new CustomUserMessageAuthenticationException("Email/password manquants",[],412);
        }
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );
        $newtentativeLogin = new TentativeLogin($request->getClientIp(), $credentials['email']);
        $this->entityManager->persist($newtentativeLogin);
        $this->entityManager->flush();
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        /*if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new CustomUserMessageAuthenticationException("Donnée manquant",["token"],412);
        }*/

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException("email Introuvable",["email"],412);
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($this->tentiveloginRepository->countRecentLoginAttempts($credentials['email']) > 5) {
            throw new CustomUserMessageAuthenticationException('Vous avez essayé de vous connecter avec un mot'.' de passe incorrect de trop nombreuses fois. Veuillez patienter svp avant de ré-essayer.',[],429);
        }
        $userexist = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
        if(is_null($userexist)){
            throw new CustomUserMessageAuthenticationException("email Introuvable",[],412);
        }
        if($this->passwordEncoder->isPasswordValid($user, $credentials['password']) == false )
        { throw new CustomUserMessageAuthenticationException("erreur des données",["password"],412); }
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

        $tentative = $this->tentiveloginRepository->findBy([ 'email' => $token->getUsername()]);
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $token->getUsername()]);
        foreach ( $tentative as $tentatives){
            $this->entityManager->remove($tentatives);
        }
        $message = "L'utilisateur a été authentifié";
        $accesstoken = $request->request->get('_csrf_token');
        if ($request->request->get('tokenadger'))
        {
            $message="L'utilisateur a bien été créé avec succès";
            $accesstoken=$request->request->get('tokenadger');
        }
        $this->entityManager->flush();
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new JsonResponse([
            'error'=> false,'message' => $message,
            'user' => ['email' => $user->getEmail(),'roles' => $user->getRoles(), 'firstname' => $user->getFirstname(),'lastename' => $user->getLastname(),'sexe' => $user->getSexe(), 'date_Naissance' => $user->getDateNaissance(), 'createdAt' => $user->getCreatedAt(), 'updateAt' => $user->getUpdateAt() ],
            'access_token' => $accesstoken,
        ]);
        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        //return new RedirectResponse($this->urlGenerator->generate('index'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
