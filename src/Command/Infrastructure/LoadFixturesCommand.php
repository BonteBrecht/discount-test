<?php
declare(strict_types=1);

namespace App\Command\Infrastructure;

use App\Util\MoneyUtil;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class LoadFixturesCommand extends Command
{
    private const CUSTOMERS = [
        ['id' => '1', 'name' => 'Coca Cola',        'since' => '2014-06-28', 'revenue' => '492.12'],
        ['id' => '2', 'name' => 'Teamleader',       'since' => '2015-01-15', 'revenue' => '1505.95'],
        ['id' => '3', 'name' => 'Jeroen De Wit',    'since' => '2016-02-11', 'revenue' => '0.00']
    ];
    private const PRODUCTS = [
        ['id' => 'A101', 'description' => 'Screwdriver',                    'category' => '1', 'price' => '9.75'],
        ['id' => 'A102', 'description' => 'Electric screwdriver',           'category' => '1', 'price' => '49.50'],
        ['id' => 'B101', 'description' => 'Basic on-off switch',            'category' => '2', 'price' => '4.99'],
        ['id' => 'B102', 'description' => 'Press button',                   'category' => '2', 'price' => '4.99'],
        ['id' => 'B103', 'description' => 'Switch with motion detector',    'category' => '2', 'price' => '12.95']
    ];

    protected static $defaultName = 'app:infrastructure:load-fixtures';

    public function __construct(
        private \PDO $db,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Load fixtures for demo project');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->loadCustomers();
        $this->loadProducts();

        (new SymfonyStyle($input, $output))->success('Fixtures loaded');

        return self::SUCCESS;
    }

    private function loadCustomers(): void
    {
        $stmt = $this->db->prepare('INSERT INTO customer (id, name, since, revenue) values (:id, :name, :since, :revenue)');
        \assert($stmt !== false);

        foreach (self::CUSTOMERS as ['id' => $id, 'name' => $name, 'since' => $since, 'revenue' => $revenue]) {
            $stmt->execute([
                'id' => $id,
                'name' => $name,
                'since' => $since,
                'revenue' => MoneyUtil::amountFromString($revenue),
            ]);
        }
    }

    private function loadProducts(): void
    {
        $stmt = $this->db->prepare('INSERT INTO product (id, description, category, price) values (:id, :description, :category, :price)');
        \assert($stmt !== false);

        foreach (self::PRODUCTS as ['id' => $id, 'description' => $description, 'category' => $category, 'price' => $price]) {
            $stmt->execute([
                'id' => $id,
                'description' => $description,
                'category' => $category,
                'price' => MoneyUtil::amountFromString($price),
            ]);
        }
    }
}
