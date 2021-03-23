<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimezoneField;

class CommandeCrudController extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the argument must be either one of these strings: 'short', 'medium', 'long', 'full'
            // or a valid ICU Datetime Pattern (see http://userguide.icu-project.org/formatparse/datetime)

            ->setTimezone('Europe/Paris')
            ->setDateTimeFormat(' dd-MM-yyyy HH:mm')

            ;
    }
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('nomPrenom'),
            TextField::new('email'),
            TextField::new('telephone'),
            TextField::new('depart'),
            TextField::new('arrivee'),
            DateTimeField::new('date'),
            NumberField::new('prixApproximatif'),
            BooleanField::new('validation'),
            BooleanField::new('effectue'),
            AssociationField::new('coupon')
        ];
    }




}
