<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use App\Entity\Article;
use App\Form\ArticleType;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreerArticleController extends AbstractController
{
    #[Route('dashboard/creer-article', name: 'app_creer_article')]
    public function index(SluggerInterface $slugger, EntityManagerInterface $manager, Request $request, UserInterface $user): Response
    {
        $article = new Article();
        
        $form_article = $this->createForm(ArticleType::class, $article); 
        $form_article->handleRequest($request);

        if($form_article->isSubmitted() &&  $form_article->isValid()){

            
            $slugify = new Slugify();
            $slug = $slugify->slugify($article->getTitreArticle());
            $article->setSlugArticle($slug);
            
            $article->setDateCreation(new DateTime());
            $article->setIdUtilisateur($user);

            $manager->persist($article);
            $manager->flush();

            $fichiers = $form_article->get('nomImage')->getData();

foreach ($fichiers as $fichier) {

    if ($fichier) {
        $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $slugger->slug($originalFilename);

        $newFilename = $safeFilename.'-'.uniqid().'.'.$fichier->guessExtension();


        try {
            $fichier->move(
                $this->getParameter('image_upload'),
                $newFilename
            );
        } catch (FileException $e) {

        }

        $image = new Image();
        $image->SetNomImage($newFilename);
        $image->SetIdArticle($article);
        $manager->persist($image);
        $manager->flush();


        //$product->setBrochureFilename($newFilename);
    }

}

            return $this->redirectToRoute('app_creer_article');
        }

        return $this->render('creer_article/index.html.twig', [
            'controller_name' => $user->getNom() . '' . $user->getPrenom(),
            'form_article' => $form_article->createView()
        ]);
    }
}