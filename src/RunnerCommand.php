<?php
namespace Bench;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use Bench\Search;

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
            ->addArgument('paths', InputArgument::IS_ARRAY, 'Search for benchmarks in different paths');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = $input->getArgument('paths');

        $files = $this->finder->files();

        foreach ($paths as $path) {
            $files->in($path);
        }

        $strategy = new IterationStrategy();
        $extractor = new MethodExtractor($this->finder);
        $benchRunner = new BenchRunner($extractor, $strategy, $output);
        $benchRunner->runBenchmarks(new PrintResult());
    }
}
