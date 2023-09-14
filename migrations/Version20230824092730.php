<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824092730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE client(
            id SERIAL PRIMARY KEY,
            name VARCHAR(32) NOT NULL,
            money DECIMAL(10, 2) NOT NULL,
            connected_order_id INT,
            waiter_id INT,
            status INT NOT NULL,
            card_number VARCHAR(32),
            card_expiration_date TIMESTAMP,
            card_cvv INT,
            payment_method VARCHAR(32) NOT NULL
        )');

        $this->addSql('CREATE TABLE kitchener (
            id SERIAL PRIMARY KEY,
            name VARCHAR(32) NOT NULL,
            restaurant_id INT,
            tips FLOAT DEFAULT NULL
        )');

        $this->addSql('CREATE TABLE menu_item (
            id SERIAL PRIMARY KEY,
            name VARCHAR(32) NOT NULL,
            type INT NOT NULL,
            price FLOAT NOT NULL,
            time VARCHAR(8) NOT NULL,
            restaurant_id INT
        )');

        $this->addSql('CREATE TABLE "order" (
            id SERIAL PRIMARY KEY,
            client_id INT NOT NULL,
            waiter_id INT,
            kitchener_id INT,
            status INT,
            tips INT
        )');

        $this->addSql('CREATE TABLE order_item (
            id SERIAL PRIMARY KEY,
            name VARCHAR(32) NOT NULL,
            price FLOAT NOT NULL,
            type VARCHAR(32) NOT NULL,
            menu_item_id INT NOT NULL,
            connected_order_id INT
        )');

        $this->addSql("CREATE TABLE restaurant (
            id SERIAL PRIMARY KEY,
            days INT NOT NULL DEFAULT 0,
            balance FLOAT NOT NULL DEFAULT 0,
            tips_strategy INT NOT NULL,
            payment_method VARCHAR(32) NOT NULL DEFAULT 'cashPayment'
        )");

        $this->addSql('CREATE TABLE waiter (
            id SERIAL PRIMARY KEY,
            name VARCHAR(32) NOT NULL,
            restaurant_id INT,
            tips FLOAT NOT NULL DEFAULT 0
        )');

        $this->addSql('ALTER TABLE client
            ADD CONSTRAINT FK_client_waiter FOREIGN KEY (waiter_id) REFERENCES waiter (id) ON DELETE SET NULL,
            ADD CONSTRAINT FK_client_connected_order FOREIGN KEY (connected_order_id) REFERENCES "order" (id) ON DELETE SET NULL;
        ');
        $this->addSql('ALTER TABLE kitchener
            ADD CONSTRAINT FK_kitchener_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurant (id);
            ');
        $this->addSql('ALTER TABLE menu_item
            ADD CONSTRAINT FK_menu_item_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurant (id);
            ');
        $this->addSql('ALTER TABLE "order"
            ADD CONSTRAINT FK_order_client FOREIGN KEY (client_id) REFERENCES client (id),
            ADD CONSTRAINT FK_order_waiter FOREIGN KEY (waiter_id) REFERENCES waiter (id),
            ADD CONSTRAINT FK_order_kitchener FOREIGN KEY (kitchener_id) REFERENCES kitchener (id);
            ');

        $this->addSql('ALTER TABLE order_item
            ADD CONSTRAINT FK_order_item_menu_item FOREIGN KEY (menu_item_id) REFERENCES menu_item (id),
            ADD CONSTRAINT FK_order_item_connected_order FOREIGN KEY (connected_order_id) REFERENCES "order" (id);
            ');

        $this->addSql('ALTER TABLE waiter
            ADD CONSTRAINT FK_waiter_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurant (id);
            ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_client_waiter');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_client_connected_order');
        $this->addSql('ALTER TABLE kitchener DROP FOREIGN KEY FK_kitchener_restaurant');
        $this->addSql('ALTER TABLE menu_item DROP FOREIGN KEY FK_menu_item_restaurant');
        $this->addSql('ALTER TABLE "order" DROP FOREIGN KEY FK_order_client');
        $this->addSql('ALTER TABLE "order" DROP FOREIGN KEY FK_order_waiter');
        $this->addSql('ALTER TABLE "order" DROP FOREIGN KEY FK_order_kitchener');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE kitchener');
        $this->addSql('DROP TABLE menu_item');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE waiter');

    }
}
