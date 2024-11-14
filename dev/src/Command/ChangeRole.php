<?php 

namespace App\Command; 

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:change-role')]
class ChangeRole extends Command 
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager; 
    }

    protected function configure(): void
    {
        $this
            // configure an argument
            ->addArgument('username', InputArgument::REQUIRED, 'The id of the user.')
            ->addArgument('role', InputArgument::REQUIRED, 'The role to attribute.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        
        if (!$user) {
            $output->writeln("L'utilisateur n'a pas été trouvé");
            return Command::FAILURE;
        }

        //get user roles and check if role already exists
        $roles = $user->getRoles();
        if (!in_array($role, $roles)) {
            $output->writeln("1. Le rôle n'est pas attribué");
            $roles[] = $role;
        } else {
            $output->writeln("1. L'utilisateur a déjà ce rôle.");
        }

        // set the role to the user
        $user->setRoles($roles);
        $this->entityManager->flush();

        // check if the role is attribute
        $roles = $user->getRoles();
        if (in_array($role, $roles)) {
            $output->writeln("2. Le rôle a bien été ajouté à l'utilisateur");
        } else {
            $output->writeln("2. L'utilisateur n'a pas ce role.");
        }

        return Command::SUCCESS;
    }
    
}
