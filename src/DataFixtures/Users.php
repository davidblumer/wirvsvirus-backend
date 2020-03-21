<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Types\UserType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Users extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user    = new User();
        $address = new Address('Vogesenblick', 4, 'Oberkirch', '77704');

        $user->setAddress($address);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setType(UserType::BUYER);
        $user->setPaypal('https://paypal.me/spomsoree');
        $user->setEmail('wirvsvirus@spomsoree.dev');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'joshua'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}
