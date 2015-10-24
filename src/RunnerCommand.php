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
            ->addOption("min-duration", "md", InputOption::VALUE_OPTIONAL, "How log a benchmark should run in seconds", 2)
            ->addOption("min-times", "mt", InputOption::VALUE_OPTIONAL, "The minimum count of loops that a benchmark should run", 10)
            ->addArgument('paths', InputArgument::IS_ARRAY, 'Search for benchmarks in different paths');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = $input->getArgument('paths');
        $minDuration = $input->getOption("min-duration");
        $minTimes = $input->getOption("min-times");

        $files = $this->finder->files();

        foreach ($paths as $path) {
            $files->in($path);
        }

        $strategy = new IterationStrategy($minDuration, $minTimes);
        $extractor = new MethodExtractor($this->finder->getIterator());
        $benchRunner = new BenchRunner($extractor, $strategy, $output);
        $benchRunner->runBenchmarks(new PrintResult());
    }
}
