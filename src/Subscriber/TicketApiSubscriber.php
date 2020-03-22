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
     * @throws \ReflectionException
     */
    public function checkAddress(ViewEvent $event)
    {
        $request = $event->getRequest();
        $method  = $request->getMethod();
        $entity  = $event->getControllerResult();
        $user    = $this->tokenStorage->getToken()->getUser();

        if ($entity instanceof Ticket) {
            if ($method === Request::METHOD_POST) {
                $ticketAddress = $entity->getAddress();

                $entity->setCreator($user);

                if (!$ticketAddress->getLatitude() || !$ticketAddress->getLongitude()) {
                    $userAddress = $user->getAddress();

                    $entity->setAddress($userAddress);
                }
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