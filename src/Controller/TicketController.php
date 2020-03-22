<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Types\TicketStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TicketController extends AbstractController
{
    /**
     * @Route("/api/tickets/{id}/accept", name="accept", methods={"GET"})
     * @ParamConverter("ticket", class="App:Ticket")
     *
     * @param Ticket $ticket
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function accept(Ticket $ticket, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager)
    {
        $user = $tokenStorage->getToken()->getUser();;

        $ticket->setStatus(TicketStatus::IN_PROGRESS);
        $ticket->setAcceptedBy($user);

        $manager->persist($ticket);
        $manager->flush();

        return new RedirectResponse('/api/tickets/' . $ticket->getId());
    }

    /**
     * @Route("/api/tickets/{id}/reopen", name="reopen", methods={"GET"})
     * @ParamConverter("ticket", class="App:Ticket")
     *
     * @param Ticket $ticket
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function reopen(Ticket $ticket, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager)
    {
        $user = $tokenStorage->getToken()->getUser();;

        $ticket->setStatus(TicketStatus::OPEN);
        $ticket->setAcceptedBy(null);

        $manager->persist($ticket);
        $manager->flush();

        return new RedirectResponse('/api/tickets/' . $ticket->getId());
    }
}