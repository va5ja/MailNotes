<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AppParseMailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mail-notes:fetch')
            ->setDescription('MailNotes mail fetcher')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('argument');

        if ($input->getOption('option')) {
            // ...
        }

        $fd = fopen("php://stdin", "r");
        $email = "";
        while (!feof($fd)) {
            $email .= fread($fd, 1024);
        }
        fclose($fd);

        $mailParser = new \ZBateson\MailMimeParser\MailMimeParser();
        $message = $mailParser->parse($email);
        $category = ucfirst($message->getHeaderValue('subject'));
        $content = array_map('trim', explode("\n", $message->getTextContent()));
        $password = array_shift($content);
        $content = implode("\n", array_filter($content));

        if ($password === date('d') . '123') {
            $dsn = 'mysql:host=localhost;dbname=laharnar_notes;charset=utf8';
            $user = 'laharnar_nts';
            $password = 'qV$ps_-lU_24';

            try {
                $dbh = new \PDO($dsn, $user, $password);

                $stmt = $dbh->prepare('SELECT * FROM category WHERE name = ?');
                if ($stmt->execute(array($category))) {
                    $row = $stmt->fetch();
                    if ($row) {
                        $category_id = $row['id'];
                    } else {
                        $stmt = $dbh->prepare('INSERT INTO category (name, datetime) VALUES (:name, :datetime)');
                        $stmt->bindParam(':name', $category);
                        $stmt->bindParam(':datetime', date('Y-m-d H:i:s'));
                        $stmt->execute();
                        $category_id = $dbh->lastInsertId();
                    }
                }

                $stmt = $dbh->prepare('INSERT INTO note (category_id, content, datetime) VALUES (:category_id, :content, :datetime)');
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':content', $content);
                $stmt->bindParam(':datetime', date('Y-m-d H:i:s'));
                $stmt->execute();
            } catch (\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
        } else {
            echo 'Wrong token.';
        }

        $output->writeln('Command result.');
    }

}
