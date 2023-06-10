<?php

namespace ElaborateCode\RowBloom\Interpolators;

use ElaborateCode\RowBloom\InterpolatorContract;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigInterpolator implements InterpolatorContract
{
    public function interpolate(Template $template, Table $table): InterpolatedTemplate
    {
        $interpolatedRows = [];

        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader);
        $template = $twig->load('template');

        foreach ($table->toArray() as $rowData) {
            $interpolatedRows[] = $template->render($rowData);
        }

        return new InterpolatedTemplate($interpolatedRows);
    }
}
