<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInterface;
use App\View\View;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Controller extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    protected Serializer $serializer;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = SerializerBuilder::create()->build();
    }

    protected function serialize($view): array
    {
        return json_decode(
            $this->serializer->serialize(
                $view,
                'json',
                SerializationContext::create()->setSerializeNull(false)
            ),
            true
        );
    }

    protected function user(): ?UserInterface
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        /** @var UserInterface|null $user */
        $user = $this->getUser() !== null
            ? $userRepository->find($this->getUser()->getId())
            : null;

        return $user;
    }
}