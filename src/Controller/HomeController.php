<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Component\Mime\Email;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
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
    public function contact(Request $request, MailerInterface $mailer, UserRepository $userRepository
    ): Response
    {
        $admin = $userRepository->findOneBy([]);

        $message = new Message();
        $contactForm = $this->createForm(MessageType::class, $message);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = $contactForm->getData();
            $entityManager->persist($message);
            $entityManager->flush();

            $email = (new Email())
            ->from($contactForm->get('email')->getData())
            ->to($admin->getEmail())
            ->subject("J'ai un nouveau message sur mon portfolio!")
            ->html($this->renderView('message/messageEmail.html.twig', [
                'message' => $message
                ]));
            $mailer->send($email);

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
