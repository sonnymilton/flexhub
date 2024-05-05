<?php

declare(strict_types=1);

namespace App\Command\Recipe;

use App\Entity\Recipe;
use App\Flex\Recipe\Dump\RecipeDumperInterface;
use App\Repository\RecipeRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('app:recipes:dump-all')]
final class DumpAllRecipesCommand extends Command
{
    public function __construct(
        private readonly RecipeRepository $repository,
        private readonly RecipeDumperInterface $recipeDumper,
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(name: 'queue', mode: InputOption::VALUE_NONE, description: 'Dump all recipes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dumpCallback = $input->getOption('queue') ?
            fn (Recipe $recipe) => $recipe->queueDump($this->messageBus) :
            fn (Recipe $recipe) => $recipe->dump($this->recipeDumper);

        foreach ($this->repository->findAll() as $recipe) {
            $dumpCallback($recipe);
        }

        return 0;
    }
}
