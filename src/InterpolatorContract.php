<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;

interface InterpolatorContract
{
    // TODO: return htmlBody + handle page separator here
    public function interpolate(Template $template, Table $table): InterpolatedTemplate;
}
