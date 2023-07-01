<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\Types\Html;
use ElaborateCode\RowBloom\Types\Table;
use Exception;

class PhpInterpolator implements InterpolatorContract
{
    public function interpolate(Html $template, Table $table, ?int $perPage = null): Html
    {
        $body = '';
        $joinCharacter = '';
        $dataCount = count($table);
        $separatePages = ! is_null($perPage);

        foreach ($table as $i => $rowData) {
            $t = $this->render($template, $rowData);

            $body .= "{$joinCharacter}{$t}";

            if (! $separatePages) {
                continue;
            }

            if (
                ($i + 1) % $perPage === 0 &&
                ($i + 1) !== $dataCount
            ) {
                $body .= '<div style="page-break-before: always;"></div>';
            }
        }

        return Html::fromString($body);
    }

    private function render(Html $template, array $row): string
    {
        ob_start();

        extract($row);
        eval(' ?>'.$template.'<?php ');

        $output = ob_get_clean();

        if($output === false) {
            throw new Exception("Couldn't render '{$template}'");
        }

        return $output;
    }
}
