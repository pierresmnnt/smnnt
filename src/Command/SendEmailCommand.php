<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:send-email',
    description: 'Add a short description for your command',
)]
class SendEmailCommand extends Command
{
    private SymfonyStyle $io;
    private MailerInterface $mailer;
    private string $sender;

    public function __construct(MailerInterface $mailer, string $sender)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Send an email for testing')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = (new Email())
            ->from($this->sender)
            ->to($this->sender)
            ->subject('This is a test email')
            ->text('This is a test')
        ;

        $this->mailer->send($email);

        $io->success('Test email sent ! Check your inbox.');

        return Command::SUCCESS;
    }
}
