<?php

namespace RowBloom\RowBloom\Interpolators;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\RowBloomException;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

class PhpInterpolator implements InterpolatorContract
{
    public const NAME = 'PHP';

    public function __construct(protected ?Config $config = null)
    {
    }

    public function interpolate(Html $template, Table $table, int $perPage = null, Config $config = null): Html
    {
        $this->config = $config ?? $this->config;

        $body = '';
        foreach ($table as $i => $rowData) {
            $body .= $this->render($template, $rowData);

            if (is_null($perPage)) {
                continue;
            }

            if (($i + 1) % $perPage === 0 && ($i + 1) !== $table->count()) {
                $body .= '<div style="page-break-before: always;"></div>';
            }
        }

        return Html::fromString($body);
    }

    private function render(Html $template, array $row): string
    {
        $output = (function (Html $template, array $row) {
            ob_start();

            extract($row);
            eval(' ?>'.$template.'<?php ');

            return ob_get_clean();
        })($template, $row);

        if ($output === false) {
            throw new RowBloomException("Couldn't render '{$template}'");
        }

        return $output;
    }
}
