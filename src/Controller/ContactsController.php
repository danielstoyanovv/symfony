<?php

namespace App\Controller;

use App\Enum\Flash;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactsType;
use App\Service\SendWithTemplate;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ContactsController extends AbstractController
{
    /**
     * index
     * @param Request $request
     * @param SendWithTemplate $sendEmail
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     * @return Response
     * @Route("/contacts", name="app_contacts_page", methods={"GET", "POST"})
     */
    public function index(Request  $request, SendWithTemplate $sendEmail, LoggerInterface $logger, Filesystem $filesystem): Response
    {
        try {
            $form = $this->createForm(ContactsType::class);

            $form->handleRequest($request);
            $uploadedFile = null;
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('file')) {
                    $file = $form->get('file')->getData();
                    $uploadDir = $this->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR .'files' . DIRECTORY_SEPARATOR;
                    if ($file) {
                        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $uploadedFile = $file->move(
                            $uploadDir,
                            $fileName . '-' . uniqid() . '.' . $file->guessExtension()
                        );
                    }
                }

                $sendEmail->send(
                    $form->get('email')->getData(),
                    'owner@abv.bg',
                    'Contact form',
                    'contacts',
                    [
                        'question' => $form->get('question')->getData(),
                        'firstName' => $form->get('firstName')->getData(),
                        'familyName' => $form->get('familyName')->getData(),
                        'sendDate' => (new \DateTime())->format('Y-m-d H:i:s')
                    ],
                    $uploadedFile
                );

                if ($uploadedFile) {
                    $filesystem->remove($uploadedFile->getRealPath());
                }

                $this->addFlash(Flash::SUCCESS, 'Your information was send');
            }
        } catch (\Exception $exception) {
            $logger->error($exception->getMessage());
        }
        return $this->renderForm('contacts/index.html.twig', [
            'form' => $form
        ]);
    }
}
