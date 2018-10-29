<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Members;

class MembersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++)
        {
            $members = new Members();

            $members->setName("Nom $i");
            $members->setSurname("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettype("composés");
            $members->setNumberBasketRest("39" + $i);
            $members->setTown("ville $i");
            $members->setDayOfWeek("jour $i");
            $members->setPseudo("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());


            $manager->persist($members);
        }
        for ($i = 6; $i <= 10; $i++)
        {
            $members = new Members();
            $members->setName("Nom $i");
            $members->setSurname("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettype("collectés");
            $members->setNumberBasketRest("34" + $i);
            $members->setTown("ville $i");
            $members->setDayOfWeek("jour $i");
            $members->setPseudo("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());

            $manager->persist($members);
        }

        $manager->flush();
    }
}