<?php
namespace App\EventSubscriber;

use App\Entity\User;
use App\Email\Mailer;
use App\Security\TokenGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordHashSubscriber implements EventSubscriberInterface{
    private $passwordEncoder;
    private $tokenGenerator;
     /**
     * @var Mailer
     */
    private $mailer;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
    TokenGenerator $tokenGenerator,
     Mailer $mailer)
    {
        $this->passwordEncoder=$passwordEncoder;
        $this->tokenGenerator=$tokenGenerator;
        $this->mailer=$mailer;
    }
    public static function getSubscribedEvents(){
        return [
            KernelEvents::VIEW=>['hashPassword',EventPriorities::PRE_WRITE]
        ];
    }

    public function hashPassword(ViewEvent $event){
      $user=$event->getControllerResult();

      $method=$event->getRequest()->getMethod();

      if(!$user instanceof User || !in_array($method,[Request::METHOD_POST])){
          return;
      }
      $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));
      $user->setConfirmationToken($this->tokenGenerator->getRandomSecureToken());
      $this->mailer->sendConfirmationEmail($user);

    }
}