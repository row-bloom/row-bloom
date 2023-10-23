<?php

namespace RowBloom\RowBloom\Renderers;

use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\RendererContract;
use RowBloom\RowBloom\Renderers\Sizing\LengthUnit;
use RowBloom\RowBloom\Renderers\Sizing\Margin;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
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

    public function render(Html $html, Css $css, Options $options, Config $config): static
    {
        $this->html = $html;
        $this->css = $css;
        $this->options = $options;

        [$paperWidth, $paperHeight] = $this->options->resolvePaperSize(LengthUnit::MILLIMETER_UNIT);

        $margin = Margin::fromOptions($this->options)->allRawIn(LengthUnit::MILLIMETER_UNIT);

        $browsershot = Browsershot::html($this->html())
            ->newHeadless()
            ->paperSize($paperWidth, $paperHeight)->landscape(false)
            ->margins($margin['marginTop'], $margin['marginRight'], $margin['marginBottom'], $margin['marginLeft'])
            ->showBackground($this->options->printBackground)
            ->scale(1);

        if ($this->options->displayHeaderFooter) {
            $browsershot->showBrowserHeaderAndFooter()
                ->headerHtml($this->options->rawHeader ?? '')
                ->footerHtml($this->options->rawFooter ?? '');
        }

        if (! is_null($config->getChromePath())) {
            $browsershot->setChromePath($config->getChromePath());
        }

        if (! is_null($config->getNodeBinaryPath())) {
            $browsershot->setNodeBinary($config->getNodeBinaryPath());
        }

        if (! is_null($config->getNpmBinaryPath())) {
            $browsershot->setNpmBinary($config->getNpmBinaryPath());
        }

        if (! is_null($config->getNodeModulesPath())) {
            $browsershot->setNodeModulePath($config->getNodeModulesPath());
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
