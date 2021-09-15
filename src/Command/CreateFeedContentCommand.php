<?php

namespace App\Command;

use App\Repository\FeedRepository;
use App\Service\ContentGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateFeedContentCommand extends Command
{
    protected static $defaultName = 'app:create-feed-content';
    protected static $defaultDescription = 'Generate content from feed url.';

    private $contentGenerator;
    private $feedRepository;

    public function __construct(string $name = null, ContentGenerator $contentGenerator, FeedRepository $feedRepository)
    {
        parent::__construct($name);
        $this->contentGenerator = $contentGenerator;
        $this->feedRepository = $feedRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('feedId', InputArgument::REQUIRED, 'Feed id to create content')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $feedId = $input->getArgument('feedId');

        $feed = $this->feedRepository->find($feedId);
        if (null === $feed) {
            $io->error('Feed not found.');

            return Command::FAILURE;
        }

        $io->note(sprintf('You passed an argument: %s', $feed->getUrl()));

        $this->contentGenerator->fetchContent($feed);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
