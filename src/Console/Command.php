<?php namespace BackupManager\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

abstract class Command extends SymfonyCommand {

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->handle();
    }

    abstract protected function handle();

    /**
     * @return InputInterface
     */
    protected function input() {
        return $this->getApplication()->input();
    }

    /**
     * @return OutputInterface
     */
    protected function output() {
        return $this->getApplication()->output();
    }

    protected function choiceQuestion($text, array $choices, $default = null) {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $defaultText = $default ? " [{$default}]" : '';
        $question = new ChoiceQuestion("<question>{$text}{$defaultText}</question>", $choices, $default);
        return $helper->ask($this->input(), $this->output(), $question);
    }

    protected function askInput() {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $question = new Question(" > ");
        return $helper->ask($this->input(), $this->output(), $question);
    }

    protected function confirmation($text, $default = false) {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $defaultOption = $default ? '[Y/n]' : '[y/N]';
        $question = new ConfirmationQuestion("<question>{$text} {$defaultOption}</question> ", $default);
        return $helper->ask($this->input(), $this->output(), $question);
    }

    protected function info($text) {
        $this->lineBreak();
        $this->output()->writeln("<info>{$text}</info>");
        $this->lineBreak();
    }

    protected function error($text) {
        $this->output()->writeln("<error>{$text}</error>");
        $this->lineBreak();
    }

    protected function lineBreak() {
        $this->output()->writeln('');
    }
}