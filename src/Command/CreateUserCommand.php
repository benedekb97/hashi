<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Entity\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;use Symfony\Component\Console\Question\Question;use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = "hashi:create:user";

    private UserPasswordEncoderInterface $encoder;

    private UserRepository $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $userPasswordEncoder,
        UserRepository $userRepository
    )
    {
        $this->encoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;

        parent::__construct(self::$defaultName);
    }

    public function configure()
    {
        $this->setName(self::$defaultName);
        $this->setDescription('Create a new user with an encoded password');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $question = new Question('Enter the email address of the user: ');

        $email = $this->getHelper('question')->ask($input, $output, $question);

        if (null !== $this->userRepository->findByEmail($email)) {
            $output->writeln('User already exists with that email address!');

            return Command::FAILURE;
        }

        $user = new User();

        $user->setEmail($email);
        $user->setPassword($this->encoder->encodePassword($user, $email));
        $user->setCreatedAtNow();
        $user->setRoles(
            [
                UserInterface::ROLE_USER,
                UserInterface::ROLE_LOGGED_IN
            ]
        );

        $this->userRepository->add($user);

        return Command::SUCCESS;
    }
}