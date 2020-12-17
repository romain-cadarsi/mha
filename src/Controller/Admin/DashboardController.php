<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MHA Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('Commandes'),
            MenuItem::linkToCrud('Commandes', 'fa fa-file', Commande::class),
            MenuItem::linkToCrud('Clients enregistrés', 'fa fa-user', Client::class),

            MenuItem::section('Pages questions/réponses'),
            MenuItem::linkToCrud('Blogs', 'fa fa-blog', Blog::class),
            MenuItem::linktoRoute('Upload images','fa fa-images','imageSandbox'),
        ];}
}
