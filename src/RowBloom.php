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
    protected InterpolatorContract|string $interpolator;

    protected RendererContract|string $renderer;

    // ------------------------------------------------------------

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

    protected Options $options;

    // ------------------------------------------------------------

    public function __construct()
    {
        $this->options = new Options;
    }

    // TODO: save()

    public function get()
    {
        // TODO: user instance -> set driver -> default driver (I don't remember what I meant)
        $interpolator = $this->resolveInterpolator();
        $renderer = $this->resolveRenderer();

        $finalTable = $this->mergeTables();
        $finaleTemplate = $this->template();
        $finalCss = $this->mergeCss(); // ! should be optional

        $interpolatedTemplate = $interpolator->interpolate($finaleTemplate, $finalTable);

        return $renderer->render($interpolatedTemplate, $finalCss, $this->options)->get();
    }

    protected function mergeTables(): Table
    {
        foreach ($this->tablePaths as $tablePath) {
            // TODO: each path should be handled with its adequate driver
            $this->tables[] = DataCollectorFactory::make('spreadsheet')
                ->getTable($tablePath);
        }
        $data = [];
        foreach ($this->tables as $table) {
            $data += $table->toArray();
        }

        return Table::fromArray($data);
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
        // TODO: clarify stylesheets ordering or allow setting priority
        foreach ($this->css as $css) {
            $finalCss->append($css);
        }

        return $finalCss;
    }

    // ============================================================
    // Fluent build methods
    // ============================================================

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

    public function setInterpolator(InterpolatorContract|string $interpolator): static
    {
        $this->interpolator = $interpolator;

        return $this;
    }

    public function setRenderer(RendererContract|string $renderer): static
    {
        $this->renderer = $renderer;

        return $this;
    }

    // ============================================================
    //
    // ============================================================

    protected function resolveInterpolator(): InterpolatorContract
    {
        if (! isset($this->interpolator)) {
            return InterpolatorFactory::make('twig');
        }

        if ($this->interpolator instanceof InterpolatorContract) {
            // ? delegate logic to factory
            return $this->interpolator;
        }

        return InterpolatorFactory::make($this->interpolator);
    }

    protected function resolveRenderer(): RendererContract
    {
        if (! isset($this->renderer)) {
            return RendererFactory::make('html');
        }

        if ($this->renderer instanceof RendererContract) {
            // ? delegate logic to factory
            return $this->renderer;
        }

        return RendererFactory::make($this->renderer);
    }
}
