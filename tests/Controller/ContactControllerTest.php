<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group contact
 */
class ContactControllerTest extends WebTestCase
{
    public function testContact()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $buttonCrawlerNode = $crawler->selectButton('contact[save]');
        $form = $buttonCrawlerNode->form();

        $client->submit($form,[
            'contact[subject]' => 'Hello',
            'contact[message]' => 'Hello from test',
        ]);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Hello', $message->getSubject());
        $this->assertSame('send@example.com', key($message->getFrom()));
        $this->assertSame('recipient@example.com', key($message->getTo()));
        $this->assertSame(
            'Hello from test',
            $message->getBody()
        );
    }
}
