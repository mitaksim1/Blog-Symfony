<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(): Response
    {
        // 2. Les données de se form sont relié à l'entité User, alors on instancie cette classe pour après pouvoir la passer en deuxième paramètre de la fonction createForm
        $user = new User();
        // 1. On crée le form pour login d'un user en passant le nom du formulaire auquel il doit se baser
        $form = $this->createForm(RegistrationType::class, $user);

        // 3. On retourne la vue avec les variables souhaitées
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
