<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserCommand extends Command
{
    private $objectManager;
    private $passwordEncoder;
    public function __construct(ObjectManager $objectManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->objectManager = $objectManager;
        $this->passwordEncoder = $passwordEncoder;
    }
    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create a new user.')
            ->setHelp('This command allow you to create an user.')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The Username'),
                new InputArgument('email', InputArgument::REQUIRED, 'The Email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
            ))
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '=============================',
            '       Create an User        ',
            '=============================',
            '',
        ]);

        $username = $input->getArgument('username');
        if ($user = $this->objectManager->getRepository(User::class)->findOneBy(["username" => $username])) {
            throw new \Exception('Username already used.');
        }
        $email = $input->getArgument('email');
        if ($user = $this->objectManager->getRepository(User::class)->findOneBy(["email" => $email])) {
            throw new \Exception('User email already used.');
        }

        $password = $input->getArgument('password');

        $user = new User();
        if ($password && $email && $username) {
            $user->setEmail($email);
            $user->setUsername($username);
            $password = $this->passwordEncoder->encodePassword($user, $password);
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $this->objectManager->persist($user);
            $this->objectManager->flush();
            $output->writeln("<comment>User " . $username . " was created </comment> \n");
        }
    }
}