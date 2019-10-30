<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$firstName, $lastName, $email, $password, $role]) {
            $user = (new User())
                ->setEmail($email)
                ->setRoles($role);

            $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);
            // $user->setPassword('otot');

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$firstName, $lastName, $email, $password, $roles];
            ['Fred', 'Doe', 'fred_admin@funerweb.fr', 'password', ['ROLE_ADMIN']],
            ['John', 'Doe', 'john_user@funerweb.fr', 'password', ['ROLE_USER']],
        ];
    }
}