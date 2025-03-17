<?php

namespace App\Command;

use App\Exception\InvalidTypeException;
use App\Service\SortedLinkedListFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sorted-list',
    description: 'Interactive application for SortedLinkedList',
)]
class SortedListCommand extends Command
{

    private $intList;
    private $stringList;
    private $currentType = null;


    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->intList = SortedLinkedListFactory::createIntList();
        $this->stringList = SortedLinkedListFactory::createStringList();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('SortedLinkedList Demo');

        if (!$this->currentType) {
            $this->selectListType($io);
        }

        while (true) {
            $action = $io->choice(
                'Choose action',
                [
                    'add' => 'Add value',
                    'remove' => 'Remove value',
                    'show' => 'Show list',
                    'count' => 'Count elements',
                    'clear' => 'Clear list',
                    'contains' => 'Check if list contains value',
                    'first' => 'Show first element',
                    'last' => 'Show last element',
                    'switch' => 'Change list type',
                    'exit' => 'Exit'
                ]
            );

            if ($action === 'exit') {
                break;
            }

            switch ($action) {
                case 'add':
                    $this->handleAddValue($io);
                    break;
                case 'remove':
                    $this->handleRemoveValue($io);
                    break;
                case 'show':
                    $this->showList($io);
                    break;
                case 'count':
                    $this->showCount($io);
                    break;
                case 'clear':
                    $this->clearList($io);
                    break;
                case 'contains':
                    $this->checkContains($io);
                    break;
                case 'first':
                    $this->showFirst($io);
                    break;
                case 'last':
                    $this->showLast($io);
                    break;
                case 'switch':
                    $this->selectListType($io);
                    break;
            }

            $io->newLine();
        }

        $io->success('Thank you for use sorted link app');
        return Command::SUCCESS;
    }

    private function handleAddValue(SymfonyStyle $io): void
    {
        if ($this->currentType === 'int') {
            $value = $io->ask('Add only number', null, function ($input) {
                if (!is_numeric($input) || strpos($input, '.') !== false) {
                    throw new \RuntimeException('Please add only number');
                }
                return (int) $input;
            });

            try {
                $this->intList->add($value);
                $io->success("Value $value added to list");
            } catch (InvalidTypeException $e) {
                $io->error($e->getMessage());
            }
        } else {
            $value = $io->ask('Add text');

            try {
                $this->stringList->add($value);
                $io->success("Value '$value' added to list");
            } catch (InvalidTypeException $e) {
                $io->error($e->getMessage());
            }
        }
    }

    private function handleRemoveValue(SymfonyStyle $io): void
    {
        if ($this->currentType === 'int') {
            $value = $io->ask('Add number to delete', null, function ($input) {
                if (!is_numeric($input) || strpos($input, '.') !== false) {
                    throw new \RuntimeException('Please add valid number');
                }
                return (int) $input;
            });

            $removed = $this->intList->remove($value);
        } else {
            $value = $io->ask('Add text to delete');

            $removed = $this->stringList->remove($value);
        }

        if ($removed) {
            $io->success("Value deleted from list");
        } else {
            $io->warning("The value is not in the list.");
        }
    }

    private function showList(SymfonyStyle $io): void
    {
        if ($this->currentType === 'int') {
            $items = $this->intList->toArray();
        } else {
            $items = $this->stringList->toArray();
        }

        if (count($items) === 0) {
            $io->warning('List is empty');
            return;
        }

        $io->section("List content ($this->currentType):");

        $table = [];
        foreach ($items as $index => $value) {
            $table[] = ["#" . ($index + 1), $value];
        }

        $io->table(['Index', 'value'], $table);
    }

    private function showCount(SymfonyStyle $io): void
    {
        if ($this->currentType === 'int') {
            $count = $this->intList->count();
        } else {
            $count = $this->stringList->count();
        }

        $io->info("Count elements in list : $count");
    }

    private function clearList(SymfonyStyle $io): void
    {
        if ($this->currentType === 'int') {
            $this->intList->clear();
        } else {
            $this->stringList->clear();
        }

        $io->success('List is cleared');
    }

    private function checkContains(SymfonyStyle $io): void
    {
        if ($this->currentType === 'int') {
            $value = $io->ask('Enter a whole number to check', null, function ($input) {
                if (!is_numeric($input) || strpos($input, '.') !== false) {
                    throw new \RuntimeException('Add only whole number');
                }
                return (int) $input;
            });

            $contains = $this->intList->contains($value);
        } else {
            $value = $io->ask('Enter a whole text to check');

            $contains = $this->stringList->contains($value);
        }

        if ($contains) {
            $io->success("Value is in the list");
        } else {
            $io->warning("Value is not in the list");
        }
    }

    private function showFirst(SymfonyStyle $io): void
    {
        try {
            if ($this->currentType === 'int') {
                $value = $this->intList->getFirst();
            } else {
                $value = $this->stringList->getFirst();
            }

            $io->info("First value: $value");
        } catch (\UnderflowException $e) {
            $io->warning("List is empty");
        }
    }

    private function showLast(SymfonyStyle $io): void
    {
        try {
            if ($this->currentType === 'int') {
                $value = $this->intList->getLast();
            } else {
                $value = $this->stringList->getLast();
            }

            $io->info("Last value: $value");
        } catch (\UnderflowException $e) {
            $io->warning("List is empty");
        }
    }

    private function selectListType(SymfonyStyle $io): void
    {
        $this->currentType = $io->choice(
            'Choose list type',
            [
                'int' => 'Int (Only numbers)',
                'string' => 'String (Only text)'
            ]
        );

        $io->success("Selected list type: $this->currentType");
    }
}