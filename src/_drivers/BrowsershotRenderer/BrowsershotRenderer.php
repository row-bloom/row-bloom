<?php

namespace RowBloom\BrowsershotRenderer;

use RowBloom\CssLength\LengthUnit;
use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\Contract as RenderersContract;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use Spatie\Browsershot\Browsershot;

class BrowsershotRenderer implements RenderersContract
{
    public const NAME = 'Browsershot';

    protected string $rendering;

    protected Html $html;

    protected Css $css;

    protected Options $options;

    public function __construct(protected ?Config $config = null)
    {
    }

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

    public function render(Html $html, Css $css, Options $options, ?Config $config = null): static
    {
        $this->html = $html;
        $this->css = $css;
        $this->options = $options;
        $this->config = $config ?? $this->config;

        $paperSize = $this->options->resolvePaperSize();

        $margin = $this->options->margin->toFloatMap(LengthUnit::MILLIMETER);

        $browsershot = Browsershot::html($this->html())
            ->newHeadless()
            ->paperSize($paperSize->width->toMmFloat(), $paperSize->height->toMmFloat())
            ->landscape(false)
            ->margins($margin['top'], $margin['right'], $margin['bottom'], $margin['left'])
            ->showBackground($this->options->printBackground)
            ->scale(1);

        $this->headerAndFooter($browsershot);

        if (! is_null($this->getBrowsershotConfig()?->chromePath)) {
            $browsershot->setChromePath($this->getBrowsershotConfig()->chromePath);
        }

        if (! is_null($this->getBrowsershotConfig()?->nodeBinaryPath)) {
            $browsershot->setNodeBinary($this->getBrowsershotConfig()->nodeBinaryPath);
        }

        if (! is_null($this->getBrowsershotConfig()?->npmBinaryPath)) {
            $browsershot->setNpmBinary($this->getBrowsershotConfig()->npmBinaryPath);
        }

        if (! is_null($this->getBrowsershotConfig()?->nodeModulesPath)) {
            $browsershot->setNodeModulePath($this->getBrowsershotConfig()->nodeModulesPath);
        }

        $this->rendering = $browsershot->base64pdf();

        return $this;
    }

    public static function getOptionsSupport(): array
    {
        return [
            'displayHeaderFooter' => true,
            'headerTemplate' => true,
            'footerTemplate' => true,
            'printBackground' => true,
            'preferCssPageSize' => true,
            'landscape' => true,
            'format' => true,
            'width' => true,
            'height' => true,
            'margin' => true,
        ];
    }

    private function getBrowsershotConfig(): ?BrowsershotConfig
    {
        return $this->config?->getDriverConfig(BrowsershotConfig::class);
    }

    // ------------------------------------------------------------

    private function headerAndFooter(Browsershot $browsershot): void
    {
        if (! $this->options->displayHeaderFooter) {
            return;
        }

        $header = $this->options->headerTemplate ?? '';

        if ($this->config?->mergeCssToHeaderFooter) {
            $header .= "<style>{$this->css}</style>";
        }

        $browsershot->showBrowserHeaderAndFooter()
            ->headerHtml($header)
            ->footerHtml($this->options->footerTemplate ?? '');
    }

    // ============================================================
    // Html
    // ============================================================

    private function html(): string
    {
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
