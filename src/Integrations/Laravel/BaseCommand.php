<?php namespace BigName\BackupManager\Integrations\Laravel;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
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
