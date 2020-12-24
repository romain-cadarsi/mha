<?php

namespace App\Controller;

use App\Entity\Client;
use App\Kernel;
use App\Entity\Commande;
use App\Repository\BlogRepository;
use App\Service\BlogsService;
use App\Service\ClientService;
use App\Service\ImageService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Icamys\SitemapGenerator\SitemapGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Mail;


class MhaController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('mha/pages/home.html.twig', [
            'controller_name' => 'MhaController',
        ]);
    }

    /**
     * @Route("/confidentialite", name="Politique de confidentialité")
     */
    public function confidentialite()
    {

        return $this->render('mha/pages/rgpd.html.twig', [
        ]);
    }

    /**
     * @Route("/cgu", name="CGU")
     */
    public function CGU()
    {

        return $this->render('mha/pages/CGU.html.twig', [
            'light' => true
        ]);
    }

    /**
     * @Route("/cgv", name="CGV")
     */
    public function CGV()
    {

        return $this->render('mha/pages/CGV.html.twig', [
            'light' => true
        ]);
    }

    /**
     * @Route("/imageSandbox", name="imageSandbox")
     */
    public function imageSandbox()
    {
        return $this->render('mha/dragAndDropSandbox.html.twig', [
            'metaDescription' => "a simple drag and drop images sandbox",
            'title' => 'Drag it, Drop it, Copy it!'
        ]);
    }

    /**
     * @Route("/xhr/uploadImage", name="uploadImage")
     */
    public function uploadImage(Request $request, Kernel $kernel)
    {
        $uploadDirectory = 'uploads/embedImage/';
        $path = $kernel->getProjectDir() . '/public/' . $uploadDirectory;
        echo($_FILES['file']['name']);
        $image = $path . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $image)) {
            echo "File is valid, and was successfully uploaded.\n";
        } else {
            echo "Possible file upload attack!\n";
        }
        return $this->render('blank.html.twig');
    }

    /**
     * @Route("/blog/{slug}", methods = "get", name="showBlog")
     */
    public function showBlog($slug, BlogRepository $blogRepository)
    {
        $blogs = $blogRepository->findAll();
        $blog = $blogRepository->findOneBy(['slug' => $slug]);

        if (!$blog) {
            return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
        } else {
            return $this->render('mha/pages/blog.html.twig', [
                    'blog' => $blog,
                    'blogs' => $blogs]
            );
        }


    }




}
