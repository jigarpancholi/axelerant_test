<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Feed;
use App\Form\ContentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentController extends AbstractController
{
    /**
     * @Route("/content/{id}", name="content_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Feed $feed): Response
    {
        return $this->render('content/index.html.twig', [
            'feed' => $feed
        ]);
    }

    /**
     * @Route("/content/edit/{id}", name="content_edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Content $content): Response
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Content is updated.');

            return $this->redirectToRoute('content_index', ['id' => $content->getFeed()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('content/edit.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }
}
