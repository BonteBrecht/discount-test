<?php
declare(strict_types=1);

namespace App\Command\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ClearDatabaseCommand extends Command
{
    protected static $defaultName = 'app:infrastructure:clear-database';

    public function __construct(
        private \PDO $db,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Clear out the current database')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Don\'t ask for confirmation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$input->getOption('force') && !$io->confirm('Are you sure you want to clear the database?')) {
            return self::SUCCESS;
        }

        $this->db->exec('SET FOREIGN_KEY_CHECKS=0');

        $stmt = $this->db->query('SHOW TABLES');
        \assert($stmt !== false);

        $tableNames = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        \assert(\is_array($tableNames));

        foreach ($tableNames as $tableName) {
            $this->db->exec(\sprintf('DROP TABLE %s', $tableName));
        }

        $this->db->exec('SET FOREIGN_KEY_CHECKS=1');

        $io->success('Database cleared');

        return self::SUCCESS;
    }
}
