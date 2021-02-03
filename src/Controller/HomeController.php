<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/", name="home")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('home/home.html.twig');
    }

    /**
     * @Route("/contact", name="_contact", methods={"GET", "POST"})
     */
    public function contact(Request $request): Response
    {
        $message = new Message();
        $contactForm = $this->createForm(MessageType::class, $message);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Message envoyé!'
            );
            return $this->redirectToRoute('home_index');
        }

        return $this->render('home/contact.html.twig', [
            'message' => $message,
            'contactForm' => $contactForm->createView(),
        ]);

    }
}
