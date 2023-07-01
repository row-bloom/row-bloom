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

        $body = '';
        $joinCharacter = '';
        $dataCount = count($table);
        $separatePages = ! is_null($perPage);

        foreach ($table as $i => $rowData) {
            // ? refine logic here (extract to trait + $this->performInterpolation())
            $t = $template->render($rowData);

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
}
