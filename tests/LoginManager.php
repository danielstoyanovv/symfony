<?php

namespace App\Tests;

use App\Entity\User;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait LoginManager
{
    public function createLoggedInAdminUser(Object $client): User
    {
        $adminUser = UserFactory::createOne();
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->save();

        $client->loginUser($adminUser->object());

        return $adminUser->object();
    }
}