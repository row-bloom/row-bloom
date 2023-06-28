<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;

class HtmlRenderer implements RendererContract
{
    protected string $rendering;

    protected InterpolatedTemplate $interpolatedTemplate;

    protected Css $css;

    protected Options $options;

    protected function render(): static
    {
        $body = '';
        if (! is_null($this->options->perPage)) {
            foreach ($this->interpolatedTemplate->toArray() as $i => $t) {
                $body .= "\n{$t}";

                if (
                    ($i + 1) % $this->options->perPage === 0 &&
                    ($i + 1) !== count($this->interpolatedTemplate->toArray())
                ) {
                    $body .= '<div style="page-break-before: always;"></div>';
                }
            }
        } else {
            // ? implode with a special string
            $body = implode('\n', $this->interpolatedTemplate->toArray());
        }

        $this->rendering = '<!DOCTYPE html><html><head>'
            .'<title>Row bloom</title>'
            ."<style>{$this->css}</style>"
            .'</head>'
            ."<body>{$body}</body>"
            .'</html>';

        return $this;
    }

    public function getRendering(InterpolatedTemplate $interpolatedTemplate, Css $css, Options $options): string
    {
        $this->interpolatedTemplate = $interpolatedTemplate;
        $this->css = $css;
        $this->options = $options;

        $this->render();

        return $this->rendering;
    }

    public function save(File $file): bool
    {
        return $file->mustBeExtension('html')
            ->startSaving()
            ->save($this->rendering);
    }
}
