<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Service\UserManager;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function Symfony\Component\String\u;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{
    private SymfonyStyle $io;
    private UserRepository $userRepository;
    private UserManager $userManager;

    public function __construct(UserRepository $userRepository, UserManager $userManager)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
    }

    protected function configure(): void
    {
        $this
            ->setHidden(true)
            ->addArgument('email', InputArgument::REQUIRED, 'Email adress')
            ->addArgument('role', InputArgument::REQUIRED, 'Role')
            ->addArgument('password', InputArgument::REQUIRED, 'Password')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->io->title("You are going to create a new user");

        $email = $input->getArgument('email');
        if($email) {
            $this->io->text(' > <info>Email address</info>: '.$email);
        } else {
            $email = $this->io->ask('Email address', null, function ($email) {
                if(empty($email)) {
                    throw new RuntimeException("Email cannot be empty");
                }

                if (1 !== preg_match('/[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/', $email)) {
                    throw new InvalidArgumentException("This value is not a valid email address.");
                }

                return $email;
            });

            $input->setArgument('email', $email);
        }

        $role = $input->getArgument('role');
        if ($role) {
            $this->io->text(' > <info>Role</info>: '. $role);
        } else {
            $role = $this->io->choice('Role', ['ROLE_USER', 'ROLE_ADMIN']);
            if(empty($role)) {
                throw new RuntimeException("Role cannot be empty");
            }

            $input->setArgument('role', $role);
        }

        $password = $input->getArgument('password');
        if ($password) {
            $this->io->text(' > <info>Password</info>: '.u('*')->repeat(u($password)->length()));
        } else {
            $password = $this->io->askHidden('Password (hidden)', function ($password) {
                if (empty($password)) {
                    throw new RuntimeException("Password cannot be empty.");
                }

                if (u($password)->trim()->length() < 8) {
                    throw new InvalidArgumentException("Password must be at least 8 characters long.");
                }

                return $password;
            });

            $input->setArgument('password', $password);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $role = $input->getArgument('role');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $this->validateData($email);

        if (!$this->io->confirm("Are you sure you want to create this user ?", false)) {
            return 0;
        }
        
        $user = $this->userManager->createUser($email, $password, $role);

        if (!$user) {
            return Command::FAILURE;
        }

        $this->io->success(sprintf('User successfully created. Email : %s, role : %s.', $user->getEmail(), $user->getRoles()[0]));

        return Command::SUCCESS;
    }

    private function validateData(string $email): void
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $email]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }
}
