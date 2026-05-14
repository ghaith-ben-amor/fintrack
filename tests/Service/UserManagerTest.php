<?php
namespace App\Tests\Service;

use App\Entity\User;
use App\Service\UserManager;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    public function testValidUser()
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPasswordHash('password123');  // ← setPasswordHash au lieu de setPassword
        
        $manager = new UserManager();
        
        $this->assertTrue($manager->validate($user));
    }
    
    public function testInvalidEmail()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $user = new User();
        $user->setEmail('invalid-email');
        $user->setPasswordHash('password123');  // ← setPasswordHash
        
        $manager = new UserManager();
        $manager->validate($user);
    }
    
    public function testShortPassword()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPasswordHash('123');  // ← setPasswordHash
        
        $manager = new UserManager();
        $manager->validate($user);
    }
}