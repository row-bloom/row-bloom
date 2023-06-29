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
    private InterpolatorContract|string $interpolator;

    private RendererContract|string $renderer;

    // ------------------------------------------------------------

    /** @var Table[] */
    private array $tables = [];

    /** @var string[][] */
    private array $tablePaths = [];

    private ?Template $template = null;

    private ?string $templatePath = null;

    /** @var Css[] */
    private array $css = [];

    /** @var string[] */
    private array $cssPaths = [];

    private Options $options;

    // ------------------------------------------------------------

    public function __construct()
    {
        $this->options = new Options;
    }

    public function save(File $file): bool
    {
        return $this->render()->save($file);
    }

    public function get(): string
    {
        return $this->render()->get();
    }

    private function render(): RendererContract
    {
        $interpolator = $this->resolveInterpolator();
        $renderer = $this->resolveRenderer();

        $finalTable = $this->mergeTables();
        $finaleTemplate = $this->template();
        $finalCss = $this->mergeCss(); // ! should be optional

        $interpolatedTemplate = $interpolator->interpolate($finaleTemplate, $finalTable);

        return $renderer->render($interpolatedTemplate, $finalCss, $this->options);
    }

    // ------------------------------------------------------------

    private function mergeTables(): Table
    {
        $dataCollectorFactory = DataCollectorFactory::getInstance();

        foreach ($this->tablePaths as $tablePath) {
            $this->tables[] = (match (true) {
                isset($tablePath['driver']) => $dataCollectorFactory->make($tablePath['driver']),
                default => $dataCollectorFactory->makeFromPath($tablePath['path']),
            })->getTable($tablePath['path']);
        }

        $data = [];

        foreach ($this->tables as $table) {
            $data += $table->toArray();
        }

        return Table::fromArray($data);
    }

    private function template(): Template
    {
        if (! is_null($this->template) && ! is_null($this->templatePath)) {
            throw new Exception('TEMPLATE...');
        }

        if (! is_null($this->templatePath)) {
            $file = File::fromPath($this->templatePath);
            $file->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('html');

            return new Template($file->readFileContent());
        }

        if (! is_null($this->template)) {
            return $this->template;
        }

        throw new Exception('TEMPLATE...');
    }

    private function mergeCss(): Css
    {
        $finalCss = new Css('');

        foreach ($this->cssPaths as $cssPath) {
            $cssFile = File::fromPath($cssPath);
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

    // ? addSpreadsheetPath() ,addJsonPath(), ...
    public function addTablePath(string $tablePath, ?string $driver = null): static
    {
        $this->tablePaths[] = [
            'path' => $tablePath,
            'driver' => $driver,
        ];

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

    private function resolveInterpolator(): InterpolatorContract
    {
        if (! isset($this->interpolator)) {
            return InterpolatorFactory::make();
        }

        if ($this->interpolator instanceof InterpolatorContract) {
            return $this->interpolator;
        }

        return InterpolatorFactory::make($this->interpolator);
    }

    private function resolveRenderer(): RendererContract
    {
        if (! isset($this->renderer)) {
            return RendererFactory::make();
        }

        if ($this->renderer instanceof RendererContract) {
            return $this->renderer;
        }

        return RendererFactory::make($this->renderer);
    }
}
