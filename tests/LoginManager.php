<?php

namespace App\Tests;

use App\Entity\User;
use App\Factory\UserFactory;

trait LoginManager
{
    public function createLoggedInAdminUser(Object $client): User
    {
        $adminUser = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN'],
            'password' => '$2y$04$NFvGDVRHUxGttvjz2phyrOAfXDlC.5ogbaKhVe/DLPxzdQF4hq6h.'
        ]);
        $adminUser->save();

        $client->loginUser($adminUser->object());

        return $adminUser->object();
    }
}