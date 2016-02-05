<?php namespace BackupManager\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class ConsoleCommand extends Command {

    protected function choiceQuestion($text, array $choices, $default = null) {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $defaultText = $default ? " [{$default}]" : '';
        $question = new ChoiceQuestion("<question>{$text}{$defaultText}</question>", $choices, $default);
        return $helper->ask($this->getApplication()->input(), $this->getApplication()->output(), $question);
    }

    protected function askInput() {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question(" > ");
        return $helper->ask($this->getApplication()->input(), $this->getApplication()->output(), $question);
    }

    protected function lineBreak() {
        $this->getApplication()->output()->writeln('');
    }

    protected function confirmation($text, $default = false) {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $defaultOption = $default ? '[Y/n]' : '[y/N]';
        $question = new ConfirmationQuestion("<question>{$text} {$defaultOption}</question> ", $default);
        return $helper->ask($this->getApplication()->input(), $this->getApplication()->output(), $question);
    }

    protected function configurationFileExists() {
        /** @var InputInterface $input */
        $input = $this->getApplication()->input();
        return $input->getOption('config') !== null || file_exists(getcwd() . '/backupmanager.yml');
    }
}