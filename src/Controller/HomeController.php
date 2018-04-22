<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/product/{slug}", name="product")
     */
    public function product(ProductRepository $productRepository, $slug)
    {
        $product = $productRepository->findOneBySlug($slug);

        return $this->render('product/product.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/json", name="productJson")
     */
    public function jsonProduct(ProductRepository $productRepository)
    {
        $products = $productRepository->findBy([], ['name' => 'ASC']);

        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'description' => $product->getDescription()
            ];
        }

        return new JsonResponse($data);
    }
}
