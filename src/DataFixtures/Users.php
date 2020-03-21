<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Users extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user    = new User();
        $address = new Address('Vogesenblick', 4, 'Oberkirch', '77704');

        $user->setAddress($address);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPaypal('https://paypal.me/spomsoree');
        $user->setEmail('wirvsvirus@spomsoree.dev');
        $user->setPassword('joshua');

        $manager->persist($user);
        $manager->flush();
    }
}
