<?php

declare(strict_types=1);

namespace Bookshop\Interfaces\Cli;

use Bookshop\Domain\Author\Author;
use League\Tactician\CommandBus;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Bookshop\Application\Author\NewAuthorCommand as NewAuthorDto;

class NewAuthorCommand extends Command
{
    /** @var CommandBus */
    private $commandBud;

    /**
     * NewAuthorCommand constructor.
     *
     * @param CommandBus $commandBud
     */
    public function __construct(CommandBus $commandBud)
    {
        parent::__construct('bookshop:authors:new');

        $this->commandBud = $commandBud;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new author.')
            ->setHelp('This command allows you to create a new author.')
            ->addArgument('name', InputArgument::REQUIRED, 'The author name.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Author Creator',
            '==============',
            '',
        ]);

        $name = $input->getArgument('name');
        if (!is_string($name)) {
            throw new \Exception('Name argument must be a string');
        }

        /** @var Author $author */
        $author = $this->commandBud->handle(new NewAuthorDto(
            Uuid::uuid4()->toString(),
            $name
        ));

        $output->writeln([
            'Author created successfully',
            'Id: ' . $author->getId()->toString(),
            'Name: ' . $author->getName()->toString(),
        ]);
    }
}
