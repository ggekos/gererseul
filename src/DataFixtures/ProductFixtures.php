<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 12; ++$i) {
            $product = new Product();
            $product->setName('Product : '.substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), -5).$i);
            $product->setPrice(rand(0, $i + 10) * $i / 10);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
