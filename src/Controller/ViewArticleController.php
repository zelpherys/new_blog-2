<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ViewArticleController extends AbstractController
{
    #[Route('/view/article/{id}', name: 'app_view_article')]
    public function index(ArticleRepository $repoArticle,$id): Response
    {

        $articles = $repoArticle->find($id);


        return $this->render('view_article/index.html.twig', [
            'controller_name' => 'ViewArticleController',
            'articles' => $articles,
        ]);
    }
}
