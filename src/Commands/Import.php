<?php 

namespace ScreenJSON\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use ScreenJSON\Cop;
use ScreenJSON\Import AS Importers;
use ScreenJSON\Interfaces\ImportInterface;

use \Exception;

#[AsCommand(name: 'import')]
class Import extends Command
{
    protected Cop $cop;
    protected array $mimes;
    protected array $engines;
    protected ImportInterface $importer;

    protected function configure(): void
    {
        $this->addArgument ('in', InputArgument::REQUIRED, 'The file you want to import [fdx|fadein|fountain|celtx|pdf|yaml].');
        $this->addArgument ('out', InputArgument::REQUIRED, 'The ScreenJSON file you want to save.');

        $this->cop = new Cop;

        $this->mimes = [
            'celtx'    => 'text/xml',
            'fdx'      => 'text/xml',
            'fadein'   => 'application/zip',
            'fountain' => 'text/plain',
            'pdf'      => 'application/pdf',
            'yaml'     => 'text/yaml',
        ];

        $this->engines = [
            'celtx'    => Importers\Celtx::class,
            'fdx'      => Importers\FinalDraft::class,
            'fadein'   => Importers\FadeIn::class,
            'fountain' => Importers\Fountain::class,
            'pdf'      => Importers\PDF::class,
            'yaml'     => Importers\Yaml::class,
        ];
    }

    protected function execute (InputInterface $input, OutputInterface $output): int
    {
        try 
        {
            $this->cop->check ("File", $input->getArgument('in'), ['file', 'exists', 'readable']);
            $this->cop->check ("Output file", basename ($input->getArgument('out')), ['exists', 'writable']);
    
            $ext = pathinfo ($input->getArgument('in'), PATHINFO_EXTENSION);
    
            if (array_key_exists ($ext, $this->engines))
            {
                $output->writeln ($ext . " is not a supported file extension.");
    
                return COMMAND::FAILURE;
            }
    
            $mime = mime_content_type ($input->getArgument('in'));
    
            if ($mime != $this->mimes[$ext])
            {
                $output->writeln ("File has a mime type (".$mime.") incompatible with its extension (".$this->mimes[$ext].").");
    
                return COMMAND::FAILURE;
            }
    
            $screenplay = new $this->engines[$ext] ($input->getArgument('in'));
    
            return $screenplay->save (null, $input->getArgument('out'))
                ? COMMAND::SUCCESS : COMMAND::FAILURE;
        }
        catch (Exception $e)
        {
            $output->writeln ("FAILED: ".$e->getMessage());
            
            return COMMAND::FAILURE;
        }
    }
}