<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;

class AppCreateProductCommand extends Command
{
    protected static $defaultName = 'app:create-product';

    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a product')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the product')
            ->addArgument('price', InputArgument::REQUIRED, 'Price of the product')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $price = $input->getArgument('price');

        if (!is_string($name)) {
            $io->error('Your name is incorrect.');
        }

        if (!is_numeric($price)) {
            $io->error('Your price must be a float.');
        }

        $product = new Product();
        $product
            ->setName($name)
            ->setPrice($price);

        $this->objectManager->persist($product);
        $this->objectManager->flush();

        $io->success('Your product has been created.');
    }
}
