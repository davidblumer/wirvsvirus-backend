<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function apiRegister(AuthenticationUtils $authenticationUtils, Request $request)
    {
        $manager     = $this->getDoctrine()->getManager();
        $content     = $request->getContent();
        $encoders    = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer  = new Serializer($normalizers, $encoders);
        $user        = $serializer->deserialize($content, User::class, 'json');

        $address  = new Address('', '', '', '');

        $user->setRoles(['ROLE_USER']);
        $user->setAddress($address);

        /*
         *
  "address": {
    "street": "Straße",
    "houseNumber": "0",
    "postalCode": "00000",
    "city": "Musterstadt",
    "latitude": "1",
    "longitude": "2"
  },
         */

        // TODO: Fix Address

        $manager->persist($user);
        $manager->flush();

        // TODO: Fix Response
        return new JsonResponse($user);
    }
}