<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Entity\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(EntityManagerInterface $manager, Request $request, UserInterface $user): Response
    {

        $utilisateur = new Utilisateur();
        
        $form_utilisateur = $this->createForm(InscriptionType::class, $utilisateur); 
        $form_utilisateur->handleRequest($request);

        if ($form_utilisateur->isSubmitted() && $form_utilisateur->isValid()) {

            $utilisateur->setDateInscription(new \DateTime());

            // Si le formulaire a été soumis et est valide, persistez l'entité Utilisateur
            $manager->persist($utilisateur);
            $manager->flush();
    
            // Redirigez ou effectuez d'autres actions après la sauvegarde
            // Par exemple, vous pouvez rediriger l'utilisateur vers une page de confirmation
        }
        


        return $this->render('inscription/index.html.twig', [
            'controller_name' => $user->getNom() . '' . $user->getPrenom(),
            'form_utilisateur' => $form_utilisateur->createView()
        ]);
    }
}
