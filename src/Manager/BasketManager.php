<?php

namespace App\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class BasketManager
{
    private $session;
    private $productRepository;

    private $basket = [];
    private $total = 0;
    private $totalQuantity = 0;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;

        $this->buildBasket();
    }

    private function buildBasket()
    {
        if ($sessionBasket = $this->session->get('basket')) {
            $products = $this->productRepository->findById(array_keys($sessionBasket));

            foreach ($products as $product) {
                $this->basket[$product->getId()] = [
                    'product' => $product,
                    'quantity' => $sessionBasket[$product->getID()],
                ];
                $this->total += $sessionBasket[$product->getID()] * $product->getPrice();
                $this->totalQuantity += $sessionBasket[$product->getID()];
            }
        }
    }

    public function getBasket()
    {
        return $this->basket;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getTotalQuantity()
    {
        return $this->totalQuantity;
    }

    public function addToBasket($productId, $quantity)
    {
        $product = $this->productRepository->findOneById($productId);

        if (!isset($this->basket[$product->getId()])) {
            $this->basket[$product->getId()] = [
                    'product' => $product,
                    'quantity' => 0,
                ];
        }
        $this->basket[$product->getId()]['quantity'] += $quantity;

        $this->storeBasket();

        return $this->basket;
    }

    public function removeFromBasket($productId)
    {
        if (isset($this->basket[$productId])) {
            unset($this->basket[$productId]);
        }

        $this->storeBasket();

        return $this->basket;
    }

    private function storeBasket()
    {
        $sessionBasket = [];
        foreach ($this->basket as $productId => $line) {
            $sessionBasket[$productId] = $line['quantity'];
        }

        $this->session->set('basket', $sessionBasket);
    }

    public function clearBasket()
    {
        $this->session->set('basket', null);
    }
}
