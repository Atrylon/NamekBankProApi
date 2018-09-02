<?php

namespace App\Command;

use App\Entity\Master;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

//Commande de création d'utilisateurs avec le ROLE_ADMIN
class AppCreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Command to create an Admin user from an email, firstname and lastname')
            ->addArgument('email', InputArgument::REQUIRED, 'Email description')
            ->addArgument('firstname', InputArgument::REQUIRED, 'firstname description')
            ->addArgument('lastname', InputArgument::REQUIRED, 'lastname description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');

        $io->note(sprintf('Create a User for email: %s', $email));
        $master = new Master();
        $master->setEmail($email);
        $master->setFirstname($firstname);
        $master->setLastname($lastname);
        $master->setRoles(['ROLE_ADMIN, ROLE_USER']);

        $this->entityManager->persist($master);
        $this->entityManager->flush();

        $io->success(sprintf('You\'ve created an Admin-user with email: %s - firstname: %s - lastname: %s', $email, $firstname, $lastname));

    }
}
