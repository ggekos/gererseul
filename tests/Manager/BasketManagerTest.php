<?php

namespace App\Tests\Manager;

use PHPUnit\Framework\TestCase;
use App\Manager\BasketManager;

/**
 * @group basketManager
 */
class BasketManagerTest extends TestCase
{
    protected $sessionMock;
    protected $productRepositoryMock;
    protected $productMock;

    protected function setUp()
    {
        $this->sessionMock = $this->getMockBuilder("Symfony\Component\HttpFoundation\Session\SessionInterface")->getMock();
        $this->sessionMock->method("get")
            ->with($this->equalTo('basket'))
            ->will($this->returnValue([
                3 => 1
            ]));

        $this->productRepositoryMock = $this->getMockBuilder("App\Repository\ProductRepository")->disableOriginalConstructor()->getMock();
        $this->productMock = $this->getMockBuilder("App\Entity\Product")->getMock();

        $this->productRepositoryMock->expects($this->at(0))
            ->method("__call")
            ->with('findById')
            ->will($this->returnValue([
                $this->productMock
            ]));

        $this->productMock->method("getId")
            ->will($this->returnValue(3));

        $this->productMock->method("getPrice")
            ->will($this->returnValue(10));

        $this->BasketManager = new BasketManager($this->sessionMock, $this->productRepositoryMock);
    }

    public function testGetBasket()
    {
        $result = $this->BasketManager->getBasket();

        $this->assertEquals([
            3 => [
                'product' => $this->productMock,
                'quantity' => 1]
            ], $result);
    }

    public function testGetTotal()
    {
        $result = $this->BasketManager->getTotal();

        $this->assertEquals(10, $result);
    }

    public function testGetTotalQuantity()
    {
        $result = $this->BasketManager->getTotalQuantity();

        $this->assertEquals(1, $result);
    }

    public function testAddToBasket()
    {
        $this->productRepositoryMock->expects($this->at(0))
            ->method("__call")
            ->with('findOneById')
            ->will($this->returnValue(
                $this->productMock
        ));

        $result = $this->BasketManager->addToBasket(3, 1);

        $this->assertEquals([
            3 => [
                'product' => $this->productMock,
                'quantity' => 2]
            ], $result);
    }

    public function testRemoveFromBasket()
    {
        $result = $this->BasketManager->removeFromBasket(3);

        $this->assertEquals([], $result);
    }

    public function testClearBasket()
    {
        $this->sessionMock->expects($this->once())
            ->method("set")
            ->with($this->equalTo('basket'), $this->equalTo(null));

        $this->BasketManager->clearBasket();
    }
}
