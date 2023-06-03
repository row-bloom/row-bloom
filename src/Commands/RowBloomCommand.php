<?php

namespace ElaborateCode\RowBloom\Commands;

use Illuminate\Console\Command;

class RowBloomCommand extends Command
{
    public $signature = 'row-bloom';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
