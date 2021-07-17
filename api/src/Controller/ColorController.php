<?php

namespace App\Controller;

use App\Entity\Color;
use App\Form\ColorType;
use App\Repository\ColorRepository;
use App\View\Color\ListView;
use App\View\Color\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/color', name: 'color_')]
class ColorController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, ColorRepository $colorRepository): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);

        $payload = new ListView($page, $colorRepository->list($page, $size));
        return new JsonResponse($payload, Response::HTTP_OK);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $color = new Color();
        $serializer->deserialize($request->getContent(), Color::class, 'json', [
            'object_to_populate' => $color,
        ]);

        if (0 < count($errors = $validator->validate($color))) {
            $error = $errors[0]->getPropertyPath().': '.$errors[0]->getMessage();
            return new JsonResponse(['error' => $error], Response::HTTP_BAD_REQUEST);
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
