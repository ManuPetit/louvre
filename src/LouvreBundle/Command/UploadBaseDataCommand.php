<?php
/**
 * Created by PhpStorm.
 * User: Emmanuel
 * Date: 14/09/2017
 * Time: 14:08
 */

namespace LouvreBundle\Command;


use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use PHPUnit\Runner\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\DriverManager;

class UploadBaseDataCommand extends DoctrineCommand
{
    protected function configure()
    {
        $message = "This commands allows you to easily add all the basic data necessary to run "
            . "the application (countries, tickets, rates and durations) in one single step.";
        $this->setName('app:load-data')
            ->setDescription('Adds basic data')
            ->setHelp($message);
    }

    /**
     * This function will load basic data into the database.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Data loading',
            '============',
            '',
        ]);
        $filename = __DIR__ . '/../../../db/louvreDataOnly.sql';
        $sql = file_get_contents($filename);
        $output->writeln($sql);
        try {
            $connectionName = $this->getContainer()->get('doctrine')->getDefaultConnectionName();
            $this->getDoctrineConnection($connectionName)->executeQuery($sql);
            $output->writeln("All data has been loaded correctly");
        } catch (\Exception $e) {
            $output->writeln(sprintf('<error>Could not load data in database :</error>'));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }
}
