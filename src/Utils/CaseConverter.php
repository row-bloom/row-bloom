<?php

namespace RowBloom\RowBloom\Utils;

final class CaseConverter
{
    public static function snakeToCamel(string $str): string
    {
        return array_reduce(
            explode('_', $str),
            function ($acc, $word) {
                if ($acc === '') {
                    return $word;
                }

                return $acc.ucfirst($word);
            },
            ''
        );
    }
}
