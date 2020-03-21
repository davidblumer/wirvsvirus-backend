<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Security extends AbstractController
{
    /**
     * @Route("/admin/login", name="login", methods={"GET", "POST"})
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error        = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'email' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * TODO: Make secure
     * @Route("/api/login", name="api_login", methods={"POST"})
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function apiLogin(AuthenticationUtils $authenticationUtils, Request $request)
    {
        $email    = $request->get('email');
        $password = $request->get('password');
        $token    = 'Error';

        $manager = $this->getDoctrine()->getManager();
        $user    = $manager->getRepository(User::class)->findOneByEmail($email);

        if ($user && $user->getPassword() === $password) {
            $token      = rand();
            $expireDate = (new \DateTime())->add(new \DateInterval('P1D'));

            $user->setToken($token);
            $user->setTokenExpireDate($expireDate);

            $manager->persist($user);
            $manager->flush();
        }

        return new Response($token);
    }
}