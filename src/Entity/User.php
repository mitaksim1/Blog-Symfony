<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
// Grâce à ce use on pourra vérifier si les mots de passe correspondent
// Les annotations constraints permettent de valider les champs
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *  fields={"email"},
 *  message="L'email que vous avez indiqué est déjà utilisé"
 * ) // permet d'assurer qu'un user est unique en fonction d'un champ
 */
// La classe User a besoin de certaines méthodes que l'on a pas implémenté dans notre code
// C'est pour ça qu'on implement UserInterface, comme ça Symfony saura que cette entité correspond à la table de nos utilisateurs
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères") 
     */
    private $password;

    // Comme on a crée un nouveau champs dans le fichier RegistrationType, il faut l'ajouter dans l'entité
    // Pas la peinde de mettre l'ORM parce quil fait pas partie de la bdd, c'est juste un champs que l'on a ajouté à notre formulaire
    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tapé le même mot de passe")
     */
    public $confirm_password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    // Si on implémente une interface, on doit implementer ses méthodes
    public function eraseCredentials() {}

    public function getSalt() {}

    // Quel est le rôle de l'user
    public function getRoles() 
    {
        return ['ROLE_USER'];
    }
}
