<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group home
 */
class HomeControllerTest extends WebTestCase
{
    public function testHome()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertCount(12, $crawler->filter('.col-sm-4'));
    }

    public function testProduct()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $firstLink = $crawler->filterXpath('//body/div[1]/div/div[1]/div/div[1]/a')->text();

        $linksCrawler = $crawler->selectLink($firstLink);
        $link = $linksCrawler->link();

        $crawler = $client->request('GET', $link->getUri());
        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $name = $crawler->filterXpath('//body/div[1]/div/div/div/div[1]/a')->text();
        $price = $crawler->filterXpath('//body/div[1]/div/div/div/div[3]')->text();

        $this->assertContains('Product', $name);
        $this->assertRegExp('/\d+\.\d{2}/', $price);
    }
}
