<?php namespace BigName\BackupManager\Integrations\Laravel;

use Illuminate\Console\Command;
use InvalidArgumentException;

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
     * @throws InvalidArgumentException
     * @internal param $question
     * @return mixed
     */
    public function autocomplete($dialog, array $list, $default = null)
    {
        $validation = function ($item) use ($list) {
            if (!in_array($item, array_values($list))) {
                throw new InvalidArgumentException("{$item} does not exist.");
            }
            return $item;
        };
        $helper = $this->getHelperSet()->get('dialog');
        return $helper->askAndValidate($this->output, "<question>{$dialog}</question>", $validation, false, $default, $list);
    }

    /**
     * @param array $headers
     * @param array $rows
     * @internal param string $style
     * @return void
     */
    public function table(array $headers, array $rows, $style = 'default')
    {
        $table = $this->getHelperSet()->get('table');
        $table->setHeaders($headers);
        $table->setRows($rows);
        $table->render($this->output);
    }
}
