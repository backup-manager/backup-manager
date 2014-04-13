<?php namespace BigName\BackupManager\Integrations\Laravel;

use Illuminate\Console\Command;

/**
 * Class BaseCommand
 * @package BigName\BackupManager\Integrations\Laravel
 */
class BaseCommand extends Command
{
    /**
     * @param $question
     * @param array $list
     * @param null $default
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function askAndValidate($question, array $list = [], $default = null)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $validation = function($item) use ($list) {
            if ( ! in_array($item, $list)) {
                throw new InvalidArgumentException("Item named \"{$item}\" is does not exist.");
            }
            return $item;
        };
        return $dialog->askAndValidate($this->output, "<question>{$question}</question>", $validation, false, $default, $list);
    }
}
