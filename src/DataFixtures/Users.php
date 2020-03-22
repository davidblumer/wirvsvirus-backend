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
        $address = new Address();

        $address->setStreet('Vogesenblick');
        $address->setHouseNumber('4');
        $address->setPostalCode('77704');
        $address->setCity('Oberkirch');
        $address->setLatitude('9');
        $address->setLongitude('48');

        $user->setAddress($address);
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setPaypal('https://paypal.me/spomsoree');
        $user->setEmail('wirvsvirus@spomsoree.dev');
        $user->setFirstName('Joshua');
        $user->setLastName('Schumacher');
        $user->setPassword('joshua');

        $manager->persist($user);
        $manager->flush();
    }
}
