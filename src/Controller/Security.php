<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

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
     * @Route("/api/users", name="api_register", methods={"POST"})
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ExceptionInterface
     */
    public function apiRegister(Request $request)
    {
        $manager      = $this->getDoctrine()->getManager();
        $content      = $request->getContent();
        $encoders     = [new JsonEncoder()];
        $normalizers  = [new ObjectNormalizer()];
        $serializer   = new Serializer($normalizers, $encoders);
        $user         = $serializer->deserialize($content, User::class, 'json');
        $addressArray = $user->getAddress();
        $address      = $serializer->denormalize($addressArray, Address::class);

        $user->setRoles(['ROLE_USER']);
        $user->setAddress($address);

        $manager->persist($user);
        $manager->flush();

        return new RedirectResponse('/api/users/' . $user->getId());
    }
}