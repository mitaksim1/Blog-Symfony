<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    // 4. En passant Request et ObjectManager à Symfony il sait que ce controlleur doit nous fournir par injection des dépendances la requête HTTP et le manager
    // 9. Après avoir créer les contraintes pour les mot de passe dans l'entité User, on a crée un encoder dans security.yaml, pour pouvoir l'utiliser dans notre controlleur je dois appeler UserPasswordEncoderInterface
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response
    {
        // 2. Les données de se form sont relié à l'entité User, alors on instancie cette classe pour après pouvoir la passer en deuxième paramètre de la fonction createForm
        $user = new User();
        // 1. On crée le form pour login d'un user en passant le nom du formulaire auquel il doit se baser
        $form = $this->createForm(RegistrationType::class, $user);

        // 5. Après avoir s'occupé de la vue pour le form, on peut récupérer les requêtes passées
        $form->handleRequest($request);

        // 6. On vérifie que le formulaire est bien rempli et que les données sont valides
        if($form->isSubmitted() && $form->isValid()) {
            // 10. Avant de sauvegarder l'utilisateur je veux hasher son mot de passe, on avait passé l'entité user au moment de créer l'encoder, Symfony va donc "comprendre" automatiquement que c'est le mot de passe de l'utilisateur à hasher
            $hash = $encoder->encodePassword($user, $user->getPassword());
            // 11. On set le mot de passe hashé
            $user->setPassword($hash);
            // 7. On demande à manager de persister l'utilisateur qui vient de s'inscrire
            $manager->persist($user);
            // 8. On envoit les informations à la bdd
            $manager->flush();
        }

        // 3. On retourne la vue avec les variables souhaitées
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

// 8. Au moment de tester j'ai eu l'erreur suivante : 
// "Cannot autowire argument $manager of "App\Controller\SecurityController::registration()": it references interface "Doctrine\Persistence\ObjectManager" but no such service exists. You should maybe alias this interface to the existing "doctrine.orm.default_entity_manager" service.
// J'ai fait un use de EntityManagerInterface alors et ça a marché!