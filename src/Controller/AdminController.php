<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\EmailType;
use Symfony\Component\Mime\Email;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/message", name="_message_index", methods={"GET"})
     */
    public function messageIndex(MessageRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);    
    }
    
    /**
     * @Route("/message/delete/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        $this->addFlash(
            'danger',
            'Message supprimé!'
        );
        return $this->redirectToRoute('message_index');
    }

    /**
     * @Route("/send/email/{message}", name="_send_email", methods={"GET", "POST"})
     */
    public function sendEmail(Message $message, Request $request, MailerInterface $mailer): Response
    {

        $emailForm = $this->createForm(EmailType::class);
        $emailForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            $emailToSend = $emailForm->getData();

            $email = (new Email())
            ->from($this->getUser()->getEmail())
            ->to($message->getEmail())
            ->subject($emailForm->get('subject')->getData())
            ->html($this->renderView('message/sendEmail.html.twig', [
                'emailToSend' => $emailToSend,
                'message' => $message
                ]));
            $mailer->send($email);

            $this->addFlash(
                'notice',
                'Email envoyé!'
            );
            return $this->redirectToRoute('message_index');
        }
        

        return $this->render('message/sendingEmailForm.html.twig', [
            'message' => $message,
            'emailForm' => $emailForm->createView(),
        ]);  
    }

}