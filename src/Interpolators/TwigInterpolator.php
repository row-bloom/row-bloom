<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\Types\Html;
use ElaborateCode\RowBloom\Types\Table;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigInterpolator implements InterpolatorContract
{
    use GlueHtmlConcern;

    public function interpolate(Html $template, Table $table, ?int $perPage = null): Html
    {
        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader);
        $template = $twig->load('template');

        $body = '';
        $dataCount = count($table);

        foreach ($table as $i => $rowData) {
            $t = $template->render($rowData);

            $body = $this->glue($body, $t, $i, $dataCount, $perPage);
        }

        return Html::fromString($body);
    }
}
