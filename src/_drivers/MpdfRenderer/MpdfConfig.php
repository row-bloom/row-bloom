<?php

namespace RowBloom\MpdfRenderer;

class MpdfConfig
{
    public function __construct(public bool $chromePdfViewerClassesHandling = false)
    {
    }
}
