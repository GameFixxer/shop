<?php
declare(strict_types=1);
namespace App\Backend\ImportAttribute\Communication;

use App\Backend\ImportAttribute\Business\Model\Importer;
use App\Component\SymfonyContainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:importProduct';
    /**
     * @var
     */
    private  $container;

    /**
     * ImportCommand constructor.
     * @param $container
     */
    public function __construct(SymfonyContainer $container)
    {
        parent::__construct();
        $this->container = $container->getContainer();
    }


    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $import = $this->container->get(Importer::class);
        $import->import();


        // ... put here the code to run in your command

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}
