<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            ImageField::new('image')->setBasePath('images/articles/')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->setUploadDir('public\images\articles')->setRequired(false),
            TextAreaField::new('content', 'Contenu')->onlyOnForms(),
            DateTimeField::new('createdAt', "Date d'ajout")->setFormat("d/M/Y à H:m:s")->hideOnForm(),
            AssociationField::new('category', 'Catégorie'),
            DateTimeField::new('updatedAt', 'Date de mise à jour')->hideOnForm(),
            TextEditorField::new('content', 'Contenu')
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        // createEntity est exécutée lorsque je clique sur add article
        // elle permet d'exécuter du code avant d'afficher la page du form de création
        // ici, je définis une date de création et de mise à jour
        
        $article = new Article;
        $article->setCreatedAt(new \DateTime)
                ->setUpdatedAt(new \DateTime);
        return $article;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void 
    {
        // updateEntity() est exécutée lors de la soumission du formulaire de mise à jour
        $isfile = $entityInstance->getImage();

        if(!$isfile)
        {
            // cette image doit être placée dans le dossier des images
            $entityInstance->setImage('defaut.jpg');
        }

        $entityInstance->setUpdatedAt(new \DateTime);
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }
    
}
