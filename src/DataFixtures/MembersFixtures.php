<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\MembersOfBasketCollected;
use App\Entity\MembersOfBasketCompouned;

class MembersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 25; $i++)
        {
            $members = new MembersOfBasketCompouned();

            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettype("composés");
            $members->setNumberBasketRest("0");
            $members->setTown("ville $i");
            $members->setDayOfWeek("jour $i");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());


            $manager->persist($members);
        }
        for ($i = 26; $i <= 50; $i++)
        {
            $members = new MembersOfBasketCollected();
            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettype("collectés");
            $members->setNumberBasketRest($i);
            $members->setTown("ville $i");
            $members->setDayOfWeek("jour $i");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());

            $manager->persist($members);
        }

        $manager->flush();
    }
}