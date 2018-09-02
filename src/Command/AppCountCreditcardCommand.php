<?php
/**
 * Created by PhpStorm.
 * User: beren
 * Date: 02/09/2018
 * Time: 11:27
 */

namespace App\Command;


use App\Manager\CreditcardManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppCountCreditcardCommand extends Command
{
    protected static $defaultName = 'app:count-creditcards';
    private $creditcardManager;

    public function __construct(CreditcardManager $creditcardManager)
    {
        $this->creditcardManager = $creditcardManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Command that count creditcards number')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $count = $this->creditcardManager->getCountCreditcards();

        $io->success(sprintf('Il y a actuellement %s creditcrads', $count));

    }

}
