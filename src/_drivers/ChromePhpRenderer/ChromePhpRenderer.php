<?php

namespace RowBloom\ChromePhpRenderer;

use HeadlessChromium\BrowserFactory;
use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\Contract as RenderersContract;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

class ChromePhpRenderer implements RenderersContract
{
    public const NAME = 'Chrome';

    protected string $rendering;

    protected Html $html;

    protected Css $css;

    protected Options $options;

    protected array $phpChromeOptions = [];

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

        $this->phpChromeOptions = [];

        $browserFactory = new BrowserFactory($this->getChromePhpConfig()?->chromePath);
        $browser = $browserFactory->createBrowser();
        $page = $browser->createPage();

        $page->navigate('data:text/html;charset=utf8,'.rawurlencode($this->html()))
            ->waitForNavigation();

        $this->setPageFormat();
        $this->setMargin();
        $this->setHeaderAndFooter();
        $this->phpChromeOptions['displayHeaderFooter'] = $this->options->displayHeaderFooter;
        $this->phpChromeOptions['printBackground'] = $this->options->printBackground;
        $this->phpChromeOptions['preferCSSPageSize'] = $this->options->preferCssPageSize;

        $this->rendering = $page->pdf($this->phpChromeOptions)->getBase64();

        $browser->close();

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

    private function getChromePhpConfig(): ?ChromePhpConfig
    {
        return $this->config?->getDriverConfig(ChromePhpConfig::class);
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

    // ============================================================
    // Options
    // ============================================================

    private function setPageFormat(): void
    {
        $paperSize = $this->options->resolvePaperSize();

        $this->phpChromeOptions['paperWidth'] = $paperSize->width->toInFloat();
        $this->phpChromeOptions['paperHeight'] = $paperSize->height->toInFloat();
    }

    private function setHeaderAndFooter(): void
    {
        $this->phpChromeOptions['displayHeaderFooter'] = $this->options->displayHeaderFooter;

        if (! $this->options->displayHeaderFooter) {
            return;
        }

        $headerTemplate = $this->options->headerTemplate ?? '';

        if ($this->config?->mergeCssToHeaderFooter) {
            $headerTemplate .= "<style>{$this->css}</style>";
        }

        $this->phpChromeOptions['headerTemplate'] = $headerTemplate;
        $this->phpChromeOptions['footerTemplate'] = $this->options->footerTemplate ?? '';
    }

    private function setMargin(): void
    {
        $this->phpChromeOptions['marginTop'] = $this->options->margin->top->toInFloat();
        $this->phpChromeOptions['marginRight'] = $this->options->margin->right->toInFloat();
        $this->phpChromeOptions['marginBottom'] = $this->options->margin->bottom->toInFloat();
        $this->phpChromeOptions['marginLeft'] = $this->options->margin->left->toInFloat();
    }
}
