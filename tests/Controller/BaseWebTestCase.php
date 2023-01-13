<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Tests\LoginManager;

class BaseWebTestCase extends WebTestCase
{
    use LoginManager;

    public function loginAsAdmin(KernelBrowser $client): User
    {
        return $this->createLoggedInAdminUser($client);
    }
}