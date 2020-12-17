<?php

namespace App\Service;

use App\Repository\BlogRepository;
use DateTime;
use Icamys\SitemapGenerator\SitemapGenerator;
use phpDocumentor\Reflection\Types\This;

class BlogsService{

    private $blogRepository;

    public function __construct(BlogRepository $blogRepository){
        $this->blogRepository = $blogRepository;
    }
    public function getAllBlogs(){
        return $this->blogRepository->findAll();
    }

    public function getAllRoutes(){
        $blogRoutes =  [];
        foreach ($this->blogRepository->findAll() as $blog){
            array_push($blogRoutes,"/index.php/blog/" . $blog->getSlug());
        }
        return $blogRoutes;
    }

    public function generateSitemap(){
        $yourSiteUrl = 'https://mha-vtc.fr';
        $outputDir = getcwd();
        $generator = new SitemapGenerator($yourSiteUrl, $outputDir);
        $generator->toggleGZipFileCreation();
        $generator->setMaxURLsPerSitemap(50000);
        $generator->setSitemapFileName("sitemap.xml");
        $generator->setSitemapIndexFileName("sitemap-index.xml");
        $generator->addURL('/index.php', new DateTime(), 'always', 1);
        $generator->addURL('/index.php/confidentialite', new DateTime(), 'always', 0.5);
        $generator->addURL('/index.php/cgv', new DateTime(), 'always', 0.5);
        $generator->addURL('/index.php/reservation', new DateTime(), 'always', 0.9);
        foreach ($this->getAllRoutes() as $route){
            $generator->addURL($route, new DateTime(), 'always', 0.8);
        }
        $generator->createSitemap();
        $generator->writeSitemap();
        $generator->updateRobots();
        $generator->submitSitemap();

    }
}