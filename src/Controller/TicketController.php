<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Ticket;
use App\Entity\Types\TicketStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return RedirectResponse|Response
     */
    public function accept(Ticket $ticket, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager)
    {
        if ($ticket->getStatus() !== TicketStatus::OPEN) {
            return new Response('Ticket is not open.', Response::HTTP_BAD_REQUEST);
        }

        $user = $tokenStorage->getToken()->getUser();

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
     * @return RedirectResponse|Response
     */
    public function reopen(Ticket $ticket, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager)
    {
        if ($ticket->getStatus() !== TicketStatus::IN_PROGRESS) {
            return new Response('Ticket is not in progress.', Response::HTTP_BAD_REQUEST);
        }

        $ticket->setStatus(TicketStatus::OPEN);
        $ticket->setAcceptedBy(null);

        $manager->persist($ticket);
        $manager->flush();

        return new RedirectResponse('/api/tickets/' . $ticket->getId());
    }

    /**
     * @Route("/api/tickets/{id}/close", name="close", methods={"GET"})
     * @ParamConverter("ticket", class="App:Ticket")
     *
     * @param Ticket $ticket
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function close(Ticket $ticket, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager)
    {
        if ($ticket->getStatus() !== TicketStatus::IN_PROGRESS) {
            return new Response('Ticket is not in progress.', Response::HTTP_BAD_REQUEST);
        }

        $ticket->setStatus(TicketStatus::FINISHED);

        $manager->persist($ticket);
        $manager->flush();

        return new RedirectResponse('/api/tickets/' . $ticket->getId());
    }

    /**
     * @Route("/api/tickets/{id}/comment", name="comment", methods={"POST"})
     * @ParamConverter("ticket", class="App:Ticket")
     *
     * @param Ticket $ticket
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function comment(Ticket $ticket, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager, Request $request)
    {
        if ($ticket->getStatus() !== TicketStatus::OPEN) {
            return new Response('Ticket is not open.', Response::HTTP_BAD_REQUEST);
        }

        $content = json_decode($request->getContent());

        if (!isset($content->text)) {
            return new Response('Text missing.', Response::HTTP_BAD_REQUEST);
        }

        $text    = $content->text;
        $user    = $tokenStorage->getToken()->getUser();
        $comment = new Comment();

        $comment->setUser($user);
        $comment->setTicket($ticket);
        $comment->setText($text);

        $manager->persist($comment);
        $manager->flush();

        return new RedirectResponse('/api/tickets/' . $ticket->getId());
    }
}