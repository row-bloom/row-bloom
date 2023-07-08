<?php

namespace ElaborateCode\RowBloom\Renderers;

use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Options;
use ElaborateCode\RowBloom\RendererContract;
use ElaborateCode\RowBloom\Renderers\Sizing\LengthUnit;
use ElaborateCode\RowBloom\Renderers\Sizing\Margin;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Html;
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

    private Html $html;

    private Css $css;

    private Options $options;

    private Mpdf $mpdf;

    /**
     * Config and defaults
     * mode: Depends on the values of codepage and country/language codes
     * format: 'A4'
     * default_font_size: Uses the default value set in defaultCSS configuration variable for the font-size of the BODY
     * default_font: Uses the default value set in defaultCSS for the font-family of BODY unless codepage is set to 'win-1252'
     * margin_left: 15mm
     * margin_right: 15mm
     * margin_top: 16mm
     * margin_bottom: 16mm
     * margin_header: 9mm
     * margin_footer: 9mm
     * orientation: 'P' (Portrait)
     */
    // ! see: https://mpdf.github.io/reference/mpdf-functions/construct.html

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

        $this->mpdf = new Mpdf(
            $this->getMargin(),
        );

        $this->setPageFormat();
        $this->setHeaderAndFooter();
        $this->setMetadata();

        $this->mpdf->WriteHTML($this->html);

        $this->mpdf->WriteHTML($this->css, HTMLParserMode::HEADER_CSS);

        $this->rendering = base64_encode($this->mpdf->OutputBinaryData());

        return $this;
    }

    // ============================================================
    // Options
    // ============================================================

    private function setPageFormat(): void
    {
        $size = $this->options->resolvePaperSize(LengthUnit::MILLIMETER_UNIT);
        $orientation = 'p';

        $this->mpdf->_setPageSize($size, $orientation);
    }

    private function getMargin(): array
    {
        $margin = Margin::fromOptions($this->options, LengthUnit::MILLIMETER_UNIT);

        return [
            'margin_top' => $margin->getRaw('marginTop'),
            'margin_right' => $margin->getRaw('marginRight'),
            'margin_bottom' => $margin->getRaw('marginBottom'),
            'margin_left' => $margin->getRaw('marginLeft'),
            // 'margin_header' => 0,
            // 'margin_footer' => 0,
        ];
    }

    private function setHeaderAndFooter(): void
    {
        // TODO: replace | with another character

        if ($this->options->displayHeaderFooter) {
            $this->mpdf->SetHeader($this->options->rawHeader);
            $this->mpdf->SetFooter($this->options->rawFooter);
        }
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
