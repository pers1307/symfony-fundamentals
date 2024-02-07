<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SongController extends AbstractController
{
    #[Route('/api/songs/{id<\d+>}', methods: ['GET'])]
    public function getSong(int $id, LoggerInterface $logger): Response
    {
        $logger->info("Test: $id");

        return $this->json([
            'id' => $id,
            'qwerty' => 10,
        ]);
        //        return new JsonResponse(['qwerty' => 10]);
    }

    //    #[Route('/browse/{slug}', name: 'app_browse')]
    //    public function browse(string $slug = null): Response
    //    {
    //        $genre = $slug ? u(str_replace('-', ' ', $slug))->title(true) : null;
    //
    //        return $this->render('vinyl/browse.html.twig', [
    //            'genre' => $genre,
    //        ]);
    //    }
}
