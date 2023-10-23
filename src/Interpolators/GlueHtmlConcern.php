<?php

namespace RowBloom\RowBloom\Interpolators;

trait GlueHtmlConcern
{
    private function glue(string $html, string $newHtml, int $i, int $count, ?int $perPage, string $joinCharacter = ''): string
    {
        $html .= "{$joinCharacter}{$newHtml}";

        if (is_null($perPage)) {
            return $html;
        }

        if (
            ($i + 1) % $perPage === 0 &&
            ($i + 1) !== $count
        ) {
            $html .= '<div style="page-break-before: always;"></div>';
        }

        return $html;
    }
}
