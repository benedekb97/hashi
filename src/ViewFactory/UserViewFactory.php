<?php

declare(strict_types=1);

namespace App\ViewFactory;

use App\Entity\EntityInterface;
use App\Entity\Traits\ResourceInterface;
use App\Entity\UserInterface;
use App\View\UserView;

class UserViewFactory extends AbstractViewFactory
{
    /**
     * {@inheritDoc}
     *
     * @param UserInterface $user
     * @return UserView
     */
    public function create(EntityInterface $user): UserView
    {
        $view = new UserView();

        $view->email = $user->getEmail();

        $view->firstName = $user->getFirstName();
        $view->lastName = $user->getLastName();

        return $view;
    }
}