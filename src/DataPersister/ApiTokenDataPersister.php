<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Service\TokenGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ApiTokenDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $hasher;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, TokenGeneratorInterface $tokenGenerator) {
        $this->hasher = $hasher;
        $this->entityManager = $entityManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof ApiToken;
    }

    public function persist($data, array $context = [])
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data->email]);

        if ($user && $this->hasher->isPasswordValid($user, $data->password)) {
            $expiredDatetime = new \DateTime();
            $expiredDatetime
                ->setTimezone(new \DateTimeZone('Europe/Sofia'))
                ->add(new \DateInterval('P1D'));
            $data
                ->setToken($this->tokenGenerator->generate())
                ->setExpiresAt($expiredDatetime);

            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }



}
