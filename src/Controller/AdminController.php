<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\EmailType;
use Symfony\Component\Mime\Email;
use App\Repository\MessageRepository;
use App\Service\ChuckNorrisApi;
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
        $joke = new ChuckNorrisApi();
        $joke = $joke->randomJoke();

        return $this->render('admin/index.html.twig', [
            'joke' => $joke
        ]);
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
     * @Route("/message/delete/{id}", name="_message_delete", methods={"DELETE"})
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
            $admin = $this->getUser()->getEmail();

            $email = (new Email())
            ->from($admin)
            ->to($message->getEmail())
            ->addCc($admin)
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
            return $this->redirectToRoute('admin_message_index');
        }
        

        return $this->render('message/sendingEmailForm.html.twig', [
            'message' => $message,
            'emailForm' => $emailForm->createView(),
        ]);  
    }


}
