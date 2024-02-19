<?php 

namespace ScreenJSON\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use ScreenJSON\Cop;
use ScreenJSON\Validator;

#[AsCommand(name: 'validate')]
class Export extends Command
{
    protected Cop $cop;
    protected Validator $validator;

    protected function configure(): void
    {
        $this->addArgument ('in', InputArgument::REQUIRED, 'The ScreenJSON file you want to validate.');

        $this->cop = new Cop;
    }

    protected function execute (InputInterface $input, OutputInterface $output): int
    {
        $this->cop->check ("JSON file", $input->getArgument('in'), ['file', 'exists', 'readable', 'mime_json']);
        
        $this->validator = (new Validator)->examine ($input->getArgument('in'));
    
        if ( $this->validator->fails() )
        {
            print_r ($this->validator->errors());

            $output->writeln('Validation FAILED.');

            return COMMAND::FAILURE;
        }

        $output->writeln ('Your file is a valid ScreenJSON document.');

        return COMMAND::SUCCESS;
    }
}