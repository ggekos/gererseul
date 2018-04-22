<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $lorem = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";

        for ($i = 0; $i < 12; ++$i) {
            $product = new Product();

            $name = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), -5);

            $product->setName('Product : '.$name.' '.$i);
            $product->setPrice(rand(0, $i + 10) * $i / 10);
            $product->setSlug($name);
            $product->setDescription(str_shuffle($lorem));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
