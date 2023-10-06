<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(UserInterface $user, ArticleRepository $repoArticle): Response
    {
        $articles = $repoArticle->findByIdUtilisateur($user);

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => $user->getPrenom(),
            'articles' => $articles,
        ]);
    }
}