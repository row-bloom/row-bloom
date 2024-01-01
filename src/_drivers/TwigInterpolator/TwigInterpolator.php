<?php

namespace RowBloom\TwigInterpolator;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Interpolators\Contract as InterpolatorsContract;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigInterpolator implements InterpolatorsContract
{
    public const NAME = 'Twig';

    public function __construct(protected ?Config $config = null)
    {
    }

    public function interpolate(Html $template, Table $table, int $perPage = null, Config $config = null): Html
    {
        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader);
        $template = $twig->load('template');

        $body = '';
        foreach ($table as $i => $rowData) {
            $body .= $template->render($rowData);

            if (is_null($perPage)) {
                continue;
            }

            if (($i + 1) % $perPage === 0 && ($i + 1) !== $table->count()) {
                $body .= '<div style="page-break-before: always;"></div>';
            }
        }

        return Html::fromString($body);
    }
}
