<?php 

namespace ScreenJSON\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use ScreenJSON\Cop;
use ScreenJSON\Export AS Exporters;
use ScreenJSON\Interfaces\ExportInterface;
use ScreenJSON\Screenplay;
use ScreenJSON\Validator;

use \Exception;

#[AsCommand(name: 'export')]
class Export extends Command
{
    protected Cop $cop;
    protected array $engines;
    protected ExportInterface $exporter;

    protected function configure(): void
    {
        $this->addArgument ('in', InputArgument::REQUIRED, 'The ScreenJSON file you want to export.');
        $this->addArgument ('out', InputArgument::REQUIRED, 'The file you want to save [fdx|fadein|fountain|celtx|pdf|yaml].');

        $this->cop = new Cop;

        $this->engines = [
            'celtx'    => Exporters\Celtx::class,
            'fdx'      => Exporters\FinalDraft::class,
            'fadein'   => Exporters\FadeIn::class,
            'fountain' => Exporters\Fountain::class,
            'pdf'      => Exporters\PDF::class,
            'yaml'     => Exporters\Yaml::class,
        ];
    }

    protected function execute (InputInterface $input, OutputInterface $output): int
    {
        try 
        {
            $this->cop->check ("File", $input->getArgument('in'), ['file', 'exists', 'readable', 'mime_json']);
            $this->cop->check ("Output file", basename ($input->getArgument('out')), ['exists', 'writable']);
    
            $ext = pathinfo ($input->getArgument('out'), PATHINFO_EXTENSION);
    
            if (array_key_exists ($ext, $this->engines))
            {
                $output->writeln ($ext . " is not a supported file extension.");
    
                return COMMAND::FAILURE;
            }
    
            if ( (new Validator ($input->getArgument('in')))->fails() )
            {
                $output->writeln ("That file does not contain valid ScreenJSON formatting. Can't continue.");
                
                return COMMAND::FAILURE;
            }
    
            $screenplay = (new Screenplay)->open ($input->getArgument('in'));    
    
            return $screenplay->save (new $this->engines[$ext], $input->getArgument('out'))
                ? COMMAND::SUCCESS : COMMAND::FAILURE;
        }
        catch (Exception $e)
        {
            $output->writeln ("FAILED: ".$e->getMessage());
            
            return COMMAND::FAILURE;
        }
    }
}