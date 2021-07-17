<?php

namespace App\Controller;

use App\Entity\Color;
use App\Form\ColorType;
use App\Repository\ColorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/color', name: 'color_')]
class ColorController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(ColorRepository $colorRepository): JsonResponse
    {
        return new JsonResponse($colorRepository->findAll(), Response::HTTP_OK);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $color = new Color();
        $form = $this->createForm(ColorType::class, $color);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        if (!$form->isValid()) {
            $errors = $form->getErrors();
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($color);
        $entityManager->flush();

        return new JsonResponse(['id' => $color->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Color $color): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($color);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
