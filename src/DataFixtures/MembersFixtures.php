<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Members;

class MembersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 25; $i++)
        {
            $members = new Members();

            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettype("composés");
            $members->setNumberBasketCompouned("0");
            $members->setTown("ville $i");
            $members->setDayOfWeek("mardi");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());


            $manager->persist($members);
        }
        for ($i = 26; $i <= 45; $i++)
        {
            $members = new Members();
            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettypeBis("collectés");
            $members->setNumberBasketCollected($i);
            $members->setTown("ville $i");
            $members->setDayOfWeek("lundi");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());

            $manager->persist($members);
        }
        for ($i = 46; $i <= 65; $i++)
        {
            $members = new Members();
            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettypeBis("collectés");
            $members->setNumberBasketCollected(0);
            $members->setTown("ville $i");
            $members->setDayOfWeek("mardi");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());

            $manager->persist($members);
        }
        for ($i = 66; $i <= 85; $i++)
        {
            $members = new Members();
            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettypeBis("collectés");
            $members->setNumberBasketCollected(0);
            $members->setTown("ville $i");
            $members->setDayOfWeek("mercredi");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());

            $manager->persist($members);
        }
        for ($i = 86; $i <= 105; $i++)
        {
            $members = new Members();
            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettypeBis("collectés");
            $members->setNumberBasketCollected(0);
            $members->setTown("ville $i");
            $members->setDayOfWeek("jeudi");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());

            $manager->persist($members);
        }
        for ($i = 106; $i <= 125; $i++)
        {
            $members = new Members();
            $members->setName("Nom $i");
            $members->setFirstName("Prénom $i");
            $members->setEmail("mail@$i");
            $members->setBaskettypeBis("collectés");
            $members->setNumberBasketCollected(0);
            $members->setTown("ville $i");
            $members->setDayOfWeek("vendredi");
            $members->setUsername("pseudo no$i");
            $members->setPassword("mot de passe no$i");
            $members->setCreatedAt(new \Datetime());

            $manager->persist($members);
        }

        $manager->flush();
    }
}