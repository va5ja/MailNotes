<?php

namespace AppBundle\Command;

use AppBundle\Entity\Category;
use AppBundle\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Exception\ContextErrorException;

class AppParseMailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mail-notes:fetch')
            ->setDescription('MailNotes mail fetcher')
            ->addOption('method', 'm', InputOption::VALUE_OPTIONAL, 'Fetch method');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $method = $input->getOption('method') ? $input->getOption('method') : 'email-piping';
        $outputMessage = '';

        switch ($method) {
            case 'email-piping':
                $fd = fopen('php://stdin', 'r');
                $rawEmailContent = "";
                while (!feof($fd)) {
                    $rawEmailContent .= fread($fd, 1024);
                }
                fclose($fd);

                $importEmail = $this->importEmail($rawEmailContent);
                if ($importEmail === true) {
                    $outputMessage = 'Successfully fetched one email.';
                } else {
                    $outputMessage = 'Error occured while fetching message: ' . $importEmail;
                }

                break;
            case 'imap':
                $hostname = $this->getContainer()->getParameter('imap_hostname');
                $username = $this->getContainer()->getParameter('imap_username');
                $password = $this->getContainer()->getParameter('imap_password');

                try {
                    $inbox = imap_open($hostname, $username, $password);
                    $emails = imap_search($inbox, 'ALL');

                    if ($emails) {
                        $outputMessage = [];
                        $fetchedEmails = 0;
                        foreach ($emails as $email_number) {
                            $rawEmailContent = imap_fetchbody($inbox, $email_number, '');
                            $importEmail = $this->importEmail($rawEmailContent);
                            if ($importEmail === true) {
                                $fetchedEmails += 1;
                            } else {
                                $outputMessage[] = "Error occured while fetching message $email_number: $importEmail";
                            }
                        }
                        $outputMessage[] = "Successfully fetched $fetchedEmails email(s).";
                    }

                    imap_close($inbox);
                } catch (ContextErrorException $e) {
                    $outputMessage = $e->getMessage();
                }
                break;
        }

        $output->writeln($outputMessage);
    }

    /**
     * @param string $rawEmailContent
     * @return bool
     */
    protected function importEmail($rawEmailContent)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $mailParser = new \ZBateson\MailMimeParser\MailMimeParser();
        $message = $mailParser->parse($rawEmailContent);
        $categoryName = ucfirst($message->getHeaderValue('subject'));
        $content = array_map('trim', explode("\n", $message->getTextContent()));
        $password = array_shift($content);
        $content = implode("\n", array_filter($content));
        $return = true;

        if ($password === date('d') . '123') {
            $category = $em->getRepository('AppBundle:Category')->findBy(['name' => $categoryName]);
            if (!$category) {
                $category = new Category();
                $category->setName($categoryName);
                $category->setDatetime(new \DateTime());
                $em->persist($category);
                $em->flush();
            }

            $note = new Note();
            $note->setCategory($category);
            $note->setContent($content);
            $note->setDatetime(new \DateTime());
            $em->persist($note);
            $em->flush();
        } else {
            $return = 'Wrong token.';
        }

        return $return;
    }

}
