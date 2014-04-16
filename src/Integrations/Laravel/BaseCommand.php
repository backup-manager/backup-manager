<?php namespace BigName\BackupManager\Integrations\Laravel;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Question\Question;

/**
 * Class BaseCommand
 * @package BigName\BackupManager\Integrations\Laravel
 */
class BaseCommand extends Command
{
    /**
     * @param $dialog
     * @param array $list
     * @param null $default
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @internal param $question
     * @return mixed
     */
    protected function autocomplete($dialog, array $list, $default = null)
    {
        $helper = $this->getHelperSet()->get('question');
        $question = new Question("<question>{$dialog}</question>", $default);
        $question->setAutocompleterValues($list);
        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * @param array $headers
     * @param array $rows
     * @param string $style
     * @return void
     */
    protected function table(array $headers, array $rows, $style = 'default')
    {
        $table = new Table($this->output);
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->setStyle($style);
        $table->render();
    }
}
