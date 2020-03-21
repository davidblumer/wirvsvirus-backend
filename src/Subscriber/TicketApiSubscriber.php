<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Ticket;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TicketApiSubscriber implements EventSubscriberInterface
{
    /**
     * @var  TokenStorageInterface $tokenStorage
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $storage
     */
    public function __construct(TokenStorageInterface $storage)
    {
        $this->tokenStorage = $storage;
    }

    /**
     * @param ViewEvent $event
     */
    public function checkAddress(ViewEvent $event)
    {
        $request = $event->getRequest();
        $method  = $request->getMethod();
        $entity  = $event->getControllerResult();

        if ($method === Request::METHOD_POST && $entity instanceof Ticket) {
            $ticketAddress = $entity->getAddress();

            if ($ticketAddress) {
                $user        = $this->tokenStorage->getToken()->getUser();
                $userAddress = $user->getAddress();

                $entity->setCreator($user);
                $entity->setAddress($userAddress);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['checkAddress', EventPriorities::PRE_WRITE],
        ];
    }
}