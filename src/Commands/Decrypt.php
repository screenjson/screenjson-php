<?php 

namespace ScreenJSON\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use ScreenJSON\Cop;
use ScreenJSON\Decrypter;
use ScreenJSON\Validator;

use \Exception;

#[AsCommand(name: 'decrypt')]
class Decrypt extends Command
{
    protected Cop $cop;
    protected Decrypter $decrypter;

    protected function configure(): void
    {
        $this->setDescription('Decrypt a ScreenJSON file.');
        $this->addArgument ('in', InputArgument::REQUIRED, 'The encrypted ScreenJSON file you want to decrypt.');
        $this->addArgument ('out', InputArgument::REQUIRED, 'The decrypted file you want to save.');
        $this->addArgument ('password', InputArgument::REQUIRED, 'The password you want to use.');

        $this->cop = new Cop;
    }

    protected function execute (InputInterface $input, OutputInterface $output): int
    {
        try 
        {
            $this->cop->check ("JSON file", $input->getArgument('in'), ['file', 'exists', 'readable', 'mime_json']);
            $this->cop->check ("Output file", dirname ($input->getArgument('out')), ['exists', 'writable']);
            $this->cop->check ("Password", $input->getArgument('password'), ['blank']);
    
            if ( (new Validator ($input->getArgument('in')))->fails() )
            {
                $output->writeln ("That file does not contain valid ScreenJSON formatting. Can't continue.");
                
                return COMMAND::FAILURE;
            }
    
            $this->decrypter = (new Decrypter)->load ($input->getArgument('in'));
    
            return $this->decrypter->save ($input->getArgument('out'), $input->getArgument('password')) 
                ? Command::SUCCESS : Command::FAILURE;
        }
        catch (Exception $e)
        {
            $output->writeln ("FAILED: ".$e->getMessage());
            
            return COMMAND::FAILURE;
        }
    }
}