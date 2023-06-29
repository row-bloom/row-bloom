<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\Types\Html;
use ElaborateCode\RowBloom\Types\Table;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigInterpolator implements InterpolatorContract
{
    public function interpolate(Html $template, Table $table, ?int $perPage = null): Html
    {
        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader);
        $template = $twig->load('template');

        $interpolatedRows = [];
        foreach ($table->toArray() as $rowData) {
            $interpolatedRows[] = $template->render($rowData);
        }

        $joinCharacter = '';

        if (is_null($perPage)) {
            return Html::fromString(implode($joinCharacter, $interpolatedRows));
        }

        $body = '';
        foreach ($interpolatedRows as $i => $t) {
            $body .= "{$joinCharacter}{$t}";

            if (
                ($i + 1) % $perPage === 0 &&
                ($i + 1) !== count($interpolatedRows)
            ) {
                $body .= '<div style="page-break-before: always;"></div>';
            }
        }

        return Html::fromString($body);
    }
}
