<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Contact;
use App\Form\ContactType;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, ObjectManager $objectManager, SessionInterface $session, \Swift_Mailer $mailer)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $objectManager->persist($contact);
            $objectManager->flush();

            $message = (new \Swift_Message($contact->getSubject()))
                ->setFrom('send@example.com')
                ->setTo('recipient@example.com')
                ->setBody(
                    $contact->getMessage(),
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash("success", "Your message has been sent.");
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
