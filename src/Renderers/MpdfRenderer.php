<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\InterpolatedTemplate;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

/**
 * https://mpdf.github.io/
 *
 * HTML to PDF
 *
 * Pros:
 * - PDF specific attributes
 *
 * Cons
 * - Weak HTML and CSS support
 * - No js
 */
class MpdfRenderer implements RendererContract
{
    private string $rendering;

    private InterpolatedTemplate $interpolatedTemplate;

    private Css $css;

    private Options $options;

    private Mpdf $mpdf;

    public function __construct()
    {
        $this->mpdf = new Mpdf;
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
        return $file->mustBeExtension('pdf')
            ->startSaving()
            ->streamFilterAppend('convert.base64-decode')
            ->save($this->rendering);
    }

    private function render(): static
    {
        $this->mpdf->_setPageSize($this->options->paperSize, $this->options->layout);

        $this->setMargins();
        $this->setHeaderAndFooter();
        $this->setMetadata();

        $body = $this->getHtmlBody();
        $this->mpdf->WriteHTML($body);

        $this->mpdf->WriteHTML($this->css, HTMLParserMode::HEADER_CSS);

        $this->rendering = base64_encode($this->mpdf->OutputBinaryData());
        // ...

        return $this;
    }

    private function getHtmlBody(): string
    {
        if (is_null($this->options?->perPage)) {
            return implode('', $this->interpolatedTemplate->toArray());
        }

        $body = '';
        foreach ($this->interpolatedTemplate->toArray() as $i => $t) {
            $body .= "\n{$t}";

            if (
                ($i + 1) % $this->options->perPage === 0 &&
                ($i + 1) !== count($this->interpolatedTemplate->toArray())
            ) {
                $body .= '<div style="page-break-before: always;"></div>';
            }
        }

        return $body;
    }

    private function setMargins(): void
    {
        // TODO: how to set margin_bottom?
        if (count($this->options->margins) === 1) {
            $this->mpdf->SetTopMargin($this->options->margins[0]);
            $this->mpdf->SetRightMargin($this->options->margins[0]);
            // $this->mpdf->SetBottomMargin($this->options->margins[0]);
            $this->mpdf->SetLeftMargin($this->options->margins[0]);
        } elseif (count($this->options->margins) === 2) {
            $this->mpdf->SetTopMargin($this->options->margins[0]);
            $this->mpdf->SetRightMargin($this->options->margins[0]);
            // $this->mpdf->SetBottomMargin($this->options->margins[1]);
            $this->mpdf->SetLeftMargin($this->options->margins[1]);
        } elseif (count($this->options->margins) >= 4) {
            $this->mpdf->SetTopMargin($this->options->margins[0]);
            $this->mpdf->SetRightMargin($this->options->margins[1]);
            // $this->mpdf->SetBottomMargin($this->options->margins[2]);
            $this->mpdf->SetLeftMargin($this->options->margins[3]);
        }
    }

    private function setHeaderAndFooter(): void
    {
        // TODO: replace | with another character
        // TODO: handle page numbering and date here

        $this->mpdf->SetHeader(implode('|', [
            $this->options->headerLeft,
            $this->options->headerCenter,
            $this->options->headerRight,
        ]));

        $this->mpdf->SetFooter(implode('|', [
            $this->options->footerLeft,
            $this->options->footerCenter,
            $this->options->footerRight,
        ]));

        // $this->mpdf->AliasNbPages();
        // $this->mpdf->SetFooter('{PAGENO}');
    }

    private function setMetadata(): void
    {
        $this->mpdf->SetTitle($this->options->metadataTitle);
        $this->mpdf->SetAuthor($this->options->metadataAuthor);
        $this->mpdf->SetSubject($this->options->metadataSubject);
        $this->mpdf->SetKeywords($this->options->metadataKeywords);
    }
}
