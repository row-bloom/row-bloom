<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigInterpolator implements InterpolatorContract
{
    public function interpolate(string $template, array $data): array
    {
        $interpolatedRows = [];

        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader);
        $template = $twig->load('template');

        foreach ($data as $rowData) {
            $interpolatedRows[] = $template->render($rowData);
        }

        return $interpolatedRows;
    }
}
