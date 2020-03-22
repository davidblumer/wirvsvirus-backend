<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Ticket;
use App\Entity\Types\TicketStatus;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users/{id}/tickets", name="user_tickets", methods={"GET"})
     * @ParamConverter("user", class="App:User")
     *
     * @param User $user
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function tickets(User $user, TokenStorageInterface $tokenStorage, EntityManagerInterface $manager)
    {
        return new RedirectResponse('/api/tickets?creator=/api/users/' . $user->getId());
    }
}