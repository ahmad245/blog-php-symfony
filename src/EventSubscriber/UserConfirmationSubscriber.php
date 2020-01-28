<?php
namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserConfirmation;
use App\Security\UserConfirmationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;

use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserConfirmationSubscriber implements EventSubscriberInterface{
   /**
     * @var UserConfirmationService
     */
    private $userConfirmationService;

    public function __construct(
     
        UserConfirmationService $userConfirmationService
    ) {
        
        $this->userConfirmationService = $userConfirmationService;
    }

    public static function getSubscribedEvents()
    { 
        return [
            KernelEvents::VIEW => [
                'confirmUser',
                EventPriorities::POST_VALIDATE
            ],
        ];
    }
    public function confirmUser(ViewEvent $event){
     
       
       $request = $event->getRequest();

       if ('api_user_confirmations_post_collection' !==
           $request->get('_route')) {
               
           return;
       }

       /** @var UserConfirmation $confirmationToken */
       $confirmationToken = $event->getControllerResult();
         
       $this->userConfirmationService->confirmUser(
           $confirmationToken->confirmationToken
       );
  
       $event->setResponse(new JsonResponse(null, Response::HTTP_OK));
   }
      
      
      
      }
