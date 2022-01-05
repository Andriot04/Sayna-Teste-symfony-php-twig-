<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class ActivitySubscriber implements EventSubscriberInterface
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security,RouterInterface $r, SessionInterface $session) {
        $this->em = $em;
        $this->security = $security;
        $this->router = $r;
        $this->session = $session;
    }

    public function onTerminate() {
        /*$user = $this->security->getUser();
        if($user){
            if (!$user->getFirstSubscriotionTrial()) {
                $user->setFinabonnement(new \DateTimeImmutable());
                $this->em->persist($user);
                $this->em->flush($user);
            }
        }*/
    }

    public static function getSubscribedEvents() {
        return [
 // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::TERMINATE => [['onTerminate', 2]],
        ];
    }
}
