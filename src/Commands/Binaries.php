<?php 

namespace ScreenJSON\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use \Exception;

#[AsCommand(name: 'binaries')]
class Binaries extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Tests for the availability of pdfotohtml and yq.');
    }

    private function pdftohtml_is_accessible () : void 
    {
        $process = new Process(['/usr/bin/pdftohtml', '-v']);

        $process->run();
        
        echo $process->getOutput();
    }

    private function pdftotext_is_accessible () : void 
    {
        $process = new Process(['/usr/bin/pdftotext', '-v']);

        $process->run();
    }

    private function yq_is_accessible () : void 
    {
        $process = new Process(['yq', '--version']);

        $process->run();
        
        echo $process->getOutput();
    }

    protected function execute (InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Output of pdftohtml -v");
        $this->pdftohtml_is_accessible ();

        $output->writeln("Output of pdftotext -v");
        $this->pdftotext_is_accessible ();

        $output->writeln("Output of yq --version");
        $this->yq_is_accessible();

        return COMMAND::SUCCESS;
    }
}