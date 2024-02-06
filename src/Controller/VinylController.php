<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Symfony\Component\String\u;

class VinylController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
//        dd(['qwe' => 123]);
        dump(['qwe' => 123]);

        return $this->render('vinyl/homepage.html.twig', [
            'title' => 'Hello yellow',
            'tracks' => [
                'qwerty' => 'Hello',
                'qwerty2' => 'yellow',
            ],
        ]);
    }

    #[Route('/browse/{slug}')]
    public function browse(string $slug = null): Response
    {
        $title = 'All';

        if ($slug) {
            $title = u(str_replace('-', ' ', $slug))->title(true);
        }

        return new Response("It's browse: $title");
    }
}
