<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\Types\Table;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigInterpolator implements InterpolatorContract
{
    public function interpolate(string $template, Table $table): array
    {
        $interpolatedRows = [];

        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader);
        $template = $twig->load('template');

        foreach ($table->toArray() as $rowData) {
            $interpolatedRows[] = $template->render($rowData);
        }

        return $interpolatedRows;
    }
}
