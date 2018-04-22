<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\ProductRepository;

class AppCsvExportCommand extends Command
{
    protected static $defaultName = 'app:csv-export';

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Make a csv file with products informations')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $filename = "public/export/".date("Ymd")."-export.csv";

        $csv = fopen($filename, "w");
        fputcsv($csv, [
            'name',
            'price',
            'description'
            ]);

        $products = $this->productRepository->findBy([], ['name' => 'ASC']);

        foreach ($products as $product) {
            fputcsv($csv, [
                $product->getName(),
                $product->getPrice(),
                $product->getDescription()
                ]);
        }

        fclose($csv);

        $io->success('Your file has been created. You can find it here : '.$filename);
    }
}
