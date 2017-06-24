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
        $outputMessage = [];

        switch ($method) {
            case 'email-piping':
                $fd = fopen('php://stdin', 'r');
                $rawEmailContent = "";
                while (!feof($fd)) {
                    $rawEmailContent .= fread($fd, 1024);
                }
                fclose($fd);

                $importEmailResponse = $this->importEmail($rawEmailContent);
                if ($importEmailResponse === true) {
                    $outputMessage[] = 'Successfully fetched one email.';
                } else {
                    $outputMessage[] = 'Error occured while fetching message: ' . $importEmailResponse;
                }

                break;
            case 'imap':
                $hostname = $this->getContainer()->getParameter('mailnotes_imap_hostname');
                $username = $this->getContainer()->getParameter('mailnotes_imap_username');
                $password = $this->getContainer()->getParameter('mailnotes_imap_password');

                try {
                    $inbox = imap_open($hostname, $username, $password);
                    $emails = imap_search($inbox, 'ALL');

                    $fetchedEmails = 0;
                    if ($emails) {
                        foreach ($emails as $emailNumber) {
                            $rawEmailContent = imap_fetchbody($inbox, $emailNumber, '');
                            $importEmailResponse = $this->importEmail($rawEmailContent);
                            if ($importEmailResponse === true) {
                                $fetchedEmails += 1;
                            } else {
                                $outputMessage[] = "Error occured while fetching message $emailNumber: $importEmailResponse";
                            }
                            imap_delete($inbox, $emailNumber);
                        }
                    }
                    $outputMessage[] = "Successfully fetched $fetchedEmails email(s).";

                    imap_expunge($inbox);
                    imap_close($inbox);
                } catch (ContextErrorException $e) {
                    $outputMessage[] = $e->getMessage();
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
        $noteDate = $message->getHeader('date')->getDatetime();
        $noteDate->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $noteTitle = $message->getHeaderValue('subject');
        $noteSlug = $this->getContainer()->get('app.slugger')->slugify($noteTitle);
        $noteContent = array_map('trim', explode("\n", $message->getTextContent()));
        $notePassword = array_shift($noteContent);
        $categoryName = array_shift($noteContent);
        $categorySlug = $this->getContainer()->get('app.slugger')->slugify($categoryName);
        $noteContent = implode("\n", array_filter($noteContent));
        $postfix = $this->getContainer()->getParameter('mailnotes_postfix');
        $return = true;

        if ($notePassword === $noteDate->format('d') . $postfix) {
            $category = $em->getRepository('AppBundle:Category')->findOneBy(['slug' => $categorySlug]);
            if (!$category) {
                $category = new Category();
                $category->setName($categoryName);
                $category->setSlug($categorySlug);
                $category->setDatetime(new \DateTime());
                $em->persist($category);
                $em->flush();
            }

            $note = $em->getRepository('AppBundle:Note')->findOneBy(['slug' => $noteSlug]);
            if (!$note) {
                $note = new Note();
                $note->setCategory($category);
                $note->setTitle($noteTitle);
                $note->setContent($noteContent);
                $note->setSlug($noteSlug);
                $note->setDatetime(new \DateTime());
                $em->persist($note);
                $em->flush();
            } else {
                $return = 'Note "' . $noteTitle . '" already exists.';
            }
        } else {
            $return = 'Wrong token.';
        }

        return $return;
    }

}
