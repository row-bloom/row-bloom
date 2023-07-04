<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\RowBloomException;
use ElaborateCode\RowBloom\Types\Html;
use ElaborateCode\RowBloom\Types\Table;

class PhpInterpolator implements InterpolatorContract
{
    use GlueHtmlConcern;

    public function interpolate(Html $template, Table $table, ?int $perPage = null): Html
    {
        $body = '';
        $dataCount = count($table);

        foreach ($table as $i => $rowData) {
            $t = $this->render($template, $rowData);

            $body = $this->glue($body, $t, $i, $dataCount, $perPage);
        }

        return Html::fromString($body);
    }

    private function render(Html $template, array $row): string
    {
        ob_start();

        extract($row);
        eval(' ?>'.$template.'<?php ');

        $output = ob_get_clean();

        if ($output === false) {
            throw new RowBloomException("Couldn't render '{$template}'");
        }

        return $output;
    }
}
