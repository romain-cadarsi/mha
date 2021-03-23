<?php

namespace App\Controller\Admin;

use App\Entity\PromotionnalCode;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PromotionnalCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PromotionnalCode::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('code')
                ->setLabel('Code promotion'),
            NumberField::new('usesLeft')
            ->setLabel("Nombre d'utilisations restantes"),
            NumberField::new('discount')->setLabel('Montant de la réduction'),
            DateField::new('expireDate')->setLabel("Date d'expiration du code Promo")
        ];
    }

}
