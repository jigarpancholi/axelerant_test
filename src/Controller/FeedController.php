<?php

namespace App\Controller;

use App\Entity\Feed;
use App\Form\FeedType;
use App\Repository\FeedRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    /**
     * @Route("/", name="feed_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(FeedRepository $feedRepository): Response
    {
        return $this->render('feed/index.html.twig', [
            'feeds' => $feedRepository->findBy([], ['id' => 'desc']),
        ]);
    }

    /**
     * @Route("/feed/new", name="feed_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $feed = new Feed();
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($feed);
            $entityManager->flush();

            $this->addFlash('success', 'Feed url is created.');

            return $this->redirectToRoute('feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('feed/new.html.twig', [
            'feed' => $feed,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/feed/{id}/edit", name="feed_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Feed $feed): Response
    {
        $form = $this->createForm(FeedType::class, $feed);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Feed url is updated.');

            return $this->redirectToRoute('feed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('feed/edit.html.twig', [
            'feed' => $feed,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/feed/{id}", name="feed_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Feed $feed): Response
    {
        if ($this->isCsrfTokenValid('delete'.$feed->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($feed);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Feed url is deleted.');

        return $this->redirectToRoute('feed_index', [], Response::HTTP_SEE_OTHER);
    }
}
