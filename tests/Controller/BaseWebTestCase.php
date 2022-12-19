<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class BaseWebTestCase extends WebTestCase
{
    public function loginAsAdmin(KernelBrowser $client): User
    {
        $adminUser = UserFactory::createOne();
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->save();

        $client->loginUser($adminUser->object());

        return $adminUser->object();
    }
}