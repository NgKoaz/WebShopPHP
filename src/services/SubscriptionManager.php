<?php

namespace App\services;

use App\Entities\Subscription;
use DateTime;
use Doctrine\ORM\EntityManager;

class SubscriptionManager
{
    public function __construct(private EntityManager $entityManager) {}

    public function hasEmail(string $email)
    {
        return $this->entityManager->getRepository(Subscription::class)->findOneBy(["email" => $email]) !== null;
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Subscription::class)->findBy([]);
    }

    public function findAllVerified(): array
    {
        return $this->entityManager->getRepository(Subscription::class)->findBy(["isVerified" => true]);
    }

    public function findByEmail(string $email): ?Subscription
    {
        return $this->entityManager->getRepository(Subscription::class)->findOneBy(["email" => $email]);
    }

    public function deleteByEmail(string $email): void
    {
        $subscription = $this->findByEmail($email);
        $this->entityManager->remove($subscription);
        $this->entityManager->flush();
    }

    public function renewSendingTime(string $email)
    {
        $email = $this->findByEmail($email);
        $email->sentVerifyEmailAt = new DateTime;
        $this->entityManager->flush();
    }

    public function subscribe(string $email)
    {
        $subscription = new Subscription();
        $subscription->email = $email;
        $subscription->isVerified = false;
        $subscription->sentVerifyEmailAt = new DateTime;

        $this->entityManager->persist($subscription);
        $this->entityManager->flush();
    }

    public function verify(string $email)
    {
        $subscription = $this->findByEmail($email);
        $subscription->isVerified = true;
        $this->entityManager->flush();
    }
}
