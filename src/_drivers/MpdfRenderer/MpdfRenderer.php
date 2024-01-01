<?php

namespace RowBloom\MpdfRenderer;

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use RowBloom\RowBloom\Config;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Options;
use RowBloom\RowBloom\Renderers\Contract as RenderersContract;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;

class MpdfRenderer implements RenderersContract
{
    public const NAME = 'mPDF';

    private string $rendering;

    private Html $html;

    private Css $css;

    private Options $options;

    private Mpdf $mpdf;

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

    /** @see https://mpdf.github.io/headers-footers/method-2.html */
    public function render(Html $html, Css $css, Options $options, ?Config $config = null): static
    {
        $this->html = $html;
        $this->css = $css;
        $this->options = $options;
        $this->config = $config ?? $this->config;

        $this->mpdf = new Mpdf($this->getMargin() + [
            'default_font_size' => 12,
            'margin_header' => 5,
            'margin_footer' => 5,
            // 'default_font' => '',
        ]);

        $this->setPageFormat();
        $this->setHeaderAndFooter();

        $this->mpdf->WriteHTML($this->html);

        $this->mpdf->WriteHTML($this->css, HTMLParserMode::HEADER_CSS);

        $this->rendering = base64_encode($this->mpdf->OutputBinaryData());

        return $this;
    }

    public static function getOptionsSupport(): array
    {
        return [
            'displayHeaderFooter' => true,
            'headerTemplate' => true,
            'footerTemplate' => true,
            'printBackground' => false,
            'preferCssPageSize' => false,
            'landscape' => true,
            'format' => true,
            'width' => true,
            'height' => true,
            'margin' => true,
        ];
    }

    // ============================================================
    // Options
    // ============================================================

    private function setPageFormat(): void
    {
        $paperSize = $this->options->resolvePaperSize();
        $orientation = 'p';

        $this->mpdf->_setPageSize(
            [$paperSize->width->toMmFloat(), $paperSize->height->toMmFloat()],
            $orientation
        );
    }

    private function getMargin(): array
    {
        return [
            'margin_top' => $this->options->margin->top->toMmFloat(),
            'margin_right' => $this->options->margin->top->toMmFloat(),
            'margin_bottom' => $this->options->margin->top->toMmFloat(),
            'margin_left' => $this->options->margin->top->toMmFloat(),
        ];
    }

    private function setHeaderAndFooter(): void
    {
        if (! $this->options->displayHeaderFooter) {
            return;
        }

        $this->setHeader();
        $this->setFooter();
    }

    private function setHeader(): void
    {
        if (is_null($this->options->headerTemplate)) {
            return;
        }

        if (! $this->enabledChromePdfViewerLikeValuesInjection()) {
            $this->mpdf->SetHTMLHeader($this->options->headerTemplate);

            return;
        }

        $this->mpdf->SetHTMLHeader(MpdfDomTransformer::fromString($this->options->headerTemplate)
            ->translateHeaderFooterClasses()
            ->toHtml());
    }

    private function setFooter(): void
    {
        if (is_null($this->options->footerTemplate)) {
            return;
        }

        if (! $this->enabledChromePdfViewerLikeValuesInjection()) {
            $this->mpdf->SetHTMLFooter($this->options->footerTemplate);

            return;
        }

        $this->mpdf->SetHTMLFooter(MpdfDomTransformer::fromString($this->options->footerTemplate)
            ->translateHeaderFooterClasses()
            ->toHtml());
    }

    private function enabledChromePdfViewerLikeValuesInjection(): bool
    {
        // TODO: rename all
        return $this->config->getDriverConfig(MpdfConfig::class)?->chromePdfViewerClassesHandling ?? false;
    }
}
