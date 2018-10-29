<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\ProdBaskComp;

class ProdBaskCompFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=10; $i++)
        {
            $products = new ProdbaskComp();

            $products->setMemberId("$i");
            $products->setNameProd("Produit no $i");
            $products->setKgOrUntity("kg");
            $products->setQuantityProd("1");

            $manager->persist($products);

        }
        for($i = 11; $i <=20; $i++)
        {
            $products = new ProdbaskComp();

            $products->setMemberId("$i");
            $products->setNameProd("Produit no $i");
            $products->setKgOrUntity("unity");
            $products->setQuantityProd("1");

            $manager->persist($products);

        }

        $manager->flush();
    }
}
