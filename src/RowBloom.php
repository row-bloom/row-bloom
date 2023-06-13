<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Interpolators\InterpolatorFactory;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;
use Exception;

class RowBloom
{
    protected DataCollectorContract $dataCollector; //

    protected InterpolatorContract $interpolator;
    // protected InterpolatorContract $renderer;

    /** @var Table[] */
    protected array $tables = [];

    /** @var string[] */
    protected array $tablePaths = [];

    protected ?Template $template = null;

    protected ?string $templatePath = null;

    /** @var array<Css> */
    protected array $css = [];

    /** @var string[] */
    protected array $cssPaths = [];

    protected array $options = [];
    // TODO: $options
    // * per_page, output_path, pdf_header, pdf_footer, page_numbers, meta(author,...)

    public function __construct()
    {
        // ? config
        $this->dataCollector = DataCollectorFactory::make();
        $this->interpolator = InterpolatorFactory::make('');
        // $this->renderer = RendererFactory::make();
    }

    // TODO: save()

    public function render()
    {
        $finalTable = $this->mergeTables();
        $finalTemplate = $this->template();
        $finalCss = $this->mergeCss();

        $interpolatedTemplate = $this->interpolator->interpolate($finalTemplate, $finalTable);

        return RendererFactory::make('html')
            ->getRendering($interpolatedTemplate, $finalCss);
    }

    protected function mergeTables(): Table
    {
        foreach ($this->tablePaths as $tablePath) {
            // TODO: each path has its own driver
            $this->tables[] = $this->dataCollector->getTable($tablePath);
        }
        $data = [];
        foreach ($this->tables as $table) {
            $data += $table->toArray();
        }

        return new Table($data);
    }

    protected function template(): Template
    {
        if (! is_null($this->template) && ! is_null($this->templatePath)) {
            throw new Exception('TEMPLATE...');
        }

        if (! is_null($this->templatePath)) {
            $file = new File($this->templatePath);
            $file->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('html');

            return new Template($file->readFileContent());
        }

        if (! is_null($this->template)) {
            return $this->template;
        }

        throw new Exception('TEMPLATE...');
    }

    protected function mergeCss(): Css
    {
        $finalCss = new Css('');

        foreach ($this->cssPaths as $cssPath) {
            $cssFile = new File($cssPath);
            $cssFile->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('css');

            $finalCss->append($cssFile->readFileContent());
        }
        // TODO: clarify stylesheets ordering
        foreach ($this->css as $css) {
            $finalCss->append($css);
        }

        return $finalCss;
    }

    // ============================================================
    // Fluent build methods
    // ============================================================

    // TODO: allow setting contracts custom strategies

    public function addTable(Table $table): static
    {
        $this->tables[] = $table;

        return $this;
    }

    public function addTablePath(string $tablePath): static
    {
        $this->tablePaths[] = $tablePath;

        return $this;
    }

    public function setTemplate(Template $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function setTemplatePath(string $templatePath): static
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function addCss(Css $css): static
    {
        $this->css[] = $css;

        return $this;
    }

    public function addCssPath(string $cssPath): static
    {
        $this->cssPaths[] = $cssPath;

        return $this;
    }

    public function setOption(string $key, mixed $value): static
    {
        $this->options[$key] = $value;

        return $this;
    }
}
