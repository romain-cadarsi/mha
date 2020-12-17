<?php

namespace App\Controller;

use App\Service\BlogsService;
use Symfony\Component\Routing\Annotation\Route;

class SEOController extends MhaController
{


    /**
     * @Route("/generate" , name="generate")
     */
    public function generate(BlogsService $blogsService)
    {
        $blogsService->generateSitemap();
        return new \Symfony\Component\HttpFoundation\Response("Sitemap et Robot.txt mis à jour");
    }
}