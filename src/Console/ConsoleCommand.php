<?php namespace BackupManager\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class ConsoleCommand extends Command {

    protected function choiceQuestion(InputInterface $input, OutputInterface $output, $text, array $choices, $default = null) {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $defaultText = $default ? " [{$default}]" : '';
        $question = new ChoiceQuestion("<question>{$text}{$defaultText}</question>", $choices, $default);
        return $helper->ask($input, $output, $question);
    }

    protected function askInput(InputInterface $input, OutputInterface $output) {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question(" > ");
        return $helper->ask($input, $output, $question);
    }

    protected function lineBreak(OutputInterface $output) {
        $output->writeln('');
    }

    protected function confirmation(InputInterface $input, OutputInterface $output, $text, $default = false) {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $defaultOption = $default ? '[Y/n]' : '[y/N]';
        $question = new ConfirmationQuestion("<question>{$text} {$defaultOption}</question> ", $default);
        return $helper->ask($input, $output, $question);
    }
}