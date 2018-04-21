<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\ProductRepository;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], ['name' => 'ASC']);

        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product")
     */
    public function product(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->findOneById($id);

        return $this->render('product/product.html.twig', [
            'product' => $product,
        ]);
    }
}
