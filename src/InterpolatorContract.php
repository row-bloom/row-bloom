<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;

interface InterpolatorContract
{
    public function interpolate(Template $template, Table $table): InterpolatedTemplate;
}
