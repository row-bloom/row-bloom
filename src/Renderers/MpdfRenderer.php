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

        $this->setPageFormat();
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
        if (is_null($this->options->perPage)) {
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

    // ============================================================
    // Options
    // ============================================================

    private function setPageFormat(): void
    {
        if (isset($this->options->format)) {
            $orientation = isset($this->options->landscape) && $this->options->landscape ? 'L' : 'P';

            $this->mpdf->_setPageSize(
                $this->options->format,
                $orientation
            );

            return;
        }

        if (isset($this->options->width) && isset($this->options->height)) {
            $this->mpdf->_setPageSize(
                [$this->options->width, $this->options->height],
                'p'
            );

            return;
        }
    }

    private function setMargins(): void
    {
        $margin = Margin::fromOptions($this->options);

        $this->mpdf->SetTopMargin($margin->get('marginTop'));
        $this->mpdf->SetRightMargin($margin->get('marginRight'));
        // $this->mpdf->SetBottomMargin($margin->get('marginBottom')); // TODO
        $this->mpdf->SetLeftMargin($margin->get('marginLeft'));
    }

    private function setHeaderAndFooter(): void
    {
        // TODO: replace | with another character
        // TODO: handle page numbering and date here

        if ($this->options->displayHeaderFooter) {
            $this->mpdf->SetHeader($this->options->rawHeader);
            $this->mpdf->SetFooter($this->options->rawFooter);
        }

        // $this->mpdf->AliasNbPages();
        // $this->mpdf->SetFooter('{PAGENO}');
    }

    private function setMetadata(): void
    {
        $this->mpdf->SetTitle($this->options->metadataTitle);
        $this->mpdf->SetAuthor($this->options->metadataAuthor);
        $this->mpdf->SetSubject($this->options->metadataSubject);
        $this->mpdf->SetCreator($this->options->metadataCreator);
        $this->mpdf->SetKeywords($this->options->metadataKeywords);
    }
}
