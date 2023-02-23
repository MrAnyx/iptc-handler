<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private EntityManager $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUsername(): void
    {
        $username = 'John Doe';
        $user = new User();
        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    public function testPassword(): void
    {
        $password = 'super_secret_password';
        $user = new User();
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testRoles(): void
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $user->setRoles(['ROLE_TEST', ...$user->getRoles()]);
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertContains('ROLE_TEST', $user->getRoles());
    }
}
