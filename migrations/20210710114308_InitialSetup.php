<?php

use Phpmig\Migration\Migration;

class InitialSetup extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $db = $this->container['db'];
        \assert($db instanceof PDO);

        $db->exec(
            <<<'SQL'
                CREATE TABLE product (
                    id VARCHAR(255) NOT NULL,
                    description VARCHAR(255) NOT NULL,
                    category VARCHAR(255) NOT NULL,
                    price INT NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
            SQL
        );

        $db->exec(
            <<<'SQL'
                CREATE TABLE customer (
                    id VARCHAR(255) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    since DATE NOT NULL,
                    revenue BIGINT NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
            SQL
        );
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
