<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Renderers\Sizing\LengthUnit;
use ElaborateCode\RowBloom\Renderers\Sizing\Margin;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;
use Spatie\Browsershot\Browsershot;

// TODO: handle binaries paths
class BrowsershotRenderer implements RendererContract
{
    protected string $rendering;

    protected Html $html;

    protected Css $css;

    protected Options $options;

    public function get(): string
    {
        return $this->rendering;
    }

    public function save(File $file): bool
    {
        return $file->mustBeExtension('pdf')
            ->startSaving()
            ->streamFilterAppend('convert.base64-decode')
            ->save($this->rendering);
    }

    public function render(Html $html, Css $css, Options $options): static
    {
        $this->html = $html;
        $this->css = $css;
        $this->options = $options;

        [$paperWidth, $paperHeight] = $this->options->resolvePaperSize(LengthUnit::MILLIMETER_UNIT);

        $margin = Margin::fromOptions($this->options)->allRawIn(LengthUnit::MILLIMETER_UNIT);

        $browsershot = Browsershot::html($this->html())
            ->paperSize($paperWidth, $paperHeight)->landscape(false)
            ->margins($margin['marginTop'], $margin['marginRight'], $margin['marginBottom'], $margin['marginLeft'])
            ->showBackground($this->options->printBackground)
            ->scale(1);

        if ($this->options->displayHeaderFooter) {
            $browsershot->showBrowserHeaderAndFooter()
                ->headerHtml($this->options->rawHeader ?? '')
                ->footerHtml($this->options->rawFooter ?? '');
        }

        $this->rendering = $browsershot->base64pdf();

        return $this;
    }

    public static function getOptionsSupport(): array
    {
        return [
            'displayHeaderFooter' => true,
            'rawHeader' => true,
            'rawFooter' => true,
            'printBackground' => true,
            'preferCSSPageSize' => true,
            'landscape' => true,
            'format' => true,
            'width' => true,
            'height' => true,
            'margin' => true,
            'metadataTitle' => false,
            'metadataAuthor' => false,
            'metadataCreator' => false,
            'metadataSubject' => false,
            'metadataKeywords' => false,
        ];
    }

    // ============================================================
    // Html
    // ============================================================

    private function html(): string
    {
        // ? same happens in HtmlRenderer
        return <<<_HTML
            <!DOCTYPE html>
            <head>
                <style>
                    $this->css
                </style>
                <title>Row bloom</title>
            </head>
            <body>
                $this->html
            </body>
            </html>
        _HTML;
    }
}
