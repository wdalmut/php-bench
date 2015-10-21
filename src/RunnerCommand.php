<?php
namespace Bench;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class RunnerCommand extends Command
{
    private $finder;

    public function __construct(Finder $finder)
    {
        parent::__construct();

        $this->finder = $finder;
    }

    public function configure()
    {
        $this->setName("run")
            ->setDescription("Run benchmarks")
            ->addOption("configuration", "c", InputOption::VALUE_NONE, 'Configuration file')
            ->addArgument('paths', InputArgument::IS_ARRAY, 'Search for benchmarks in different paths');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = $input->getArgument('paths');
        $configuration = $input->getOption("configuration");

        $files = $this->finder->files();

        foreach ($paths as $path) {
            $files->in($path);
        }

        $benchRunner = new BenchRunner($this->finder, $output);
        $benchRunner->runBenchmarks(new PrintResult());
    }
}
