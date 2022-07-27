<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager): void
    {

        // Création d’un utilisateur de type user
        for ($i = 0; $i < count(ClasseFixtures::CLASSES); $i++) {
            for ($j = 0; $j < 9; $j++) {
                $user = new User();
                $user->setUsername('student' . $i . $j);
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $user,
                    'password' . $i . $j
                );
                $user->setPassword($hashedPassword);
                $user->setClasse($this->getReference('classe_' . ClasseFixtures::CLASSES[$i]));
                $manager->persist($user);
                $this->addReference('user_' . $i . $j, $user);
            }
        }


        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'password'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);


        // Création d’un utilisateur de type “super administrateur”
        $superAdmin = new User();
        $superAdmin->setUsername('super admin');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $superAdmin,
            'password'
        );
        $superAdmin->setPassword($hashedPassword);
        $manager->persist($superAdmin);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }
}
