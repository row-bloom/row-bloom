<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Interpolators\InterpolatorContract;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Renderers\RendererContract;
use RowBloom\RowBloom\Renderers\RendererFactory;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TablePath;

class RowBloom
{
    private InterpolatorContract|string $interpolator;

    private RendererContract|string $renderer;

    /** @var (Table|TablePath)[] */
    private array $tables = [];

    private Html|File|null $template = null;

    /** @var (Css|File)[] */
    private array $css = [];

    // ------------------------------------------------------------

    // TODO: inject factories and eliminate app()->make()
    public function __construct(private Options $options, private Config $config)
    {
    }

    public function save(File|string $file): bool
    {
        $file = $file instanceof File ? $file : app()->make(File::class, ['path' => $file]);

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

        $finalTable = $this->table();
        $finaleTemplate = $this->template();
        $finalCss = $this->css();

        $interpolatedTemplate = $interpolator->interpolate($finaleTemplate, $finalTable, $this->options->perPage);

        return $renderer->render($interpolatedTemplate, $finalCss, $this->options, $this->config);
    }

    // ------------------------------------------------------------

    private function resolveInterpolator(): InterpolatorContract
    {
        if (! isset($this->interpolator)) {
            throw new RowBloomException('Interpolator must be set');
        }

        if ($this->interpolator instanceof InterpolatorContract) {
            return $this->interpolator;
        }

        return app()->make(InterpolatorFactory::class)->make($this->interpolator);
    }

    private function resolveRenderer(): RendererContract
    {
        if (! isset($this->renderer)) {
            throw new RowBloomException('Renderer must be set');
        }

        if ($this->renderer instanceof RendererContract) {
            return $this->renderer;
        }

        return app()->make(RendererFactory::class)->make($this->renderer);
    }

    private function table(): Table
    {
        if (empty($this->tables)) {
            throw new RowBloomException('A table is required');
        }

        $finalTable = Table::fromArray([]);

        foreach ($this->tables as $table) {
            $finalTable->append(match (true) {
                $table instanceof Table => $table,
                $table instanceof TablePath => $this->tableFromPath($table),
            });
        }

        return $finalTable;
    }

    private function tableFromPath(TablePath $tablePath): Table
    {
        $DataLoaderFactory = app()->make(DataLoaderFactory::class);

        $DataLoader = null;

        if ($tablePath->driver) {
            $DataLoader = $DataLoaderFactory->make($tablePath->driver);
        } else {
            $DataLoader = $DataLoaderFactory->makeFromPath($tablePath->path);
        }

        return $DataLoader->getTable($tablePath->path);
    }

    private function template(): Html
    {
        if (is_null($this->template)) {
            throw new RowBloomException('A template is required');
        }

        if ($this->template instanceof Html) {
            return $this->template;
        }

        if ($this->template instanceof File) {
            return Html::fromString($this->template->readFileContent());
        }
    }

    private function css(): Css
    {
        $finalCss = Css::fromString('');

        foreach ($this->css as $css) {
            if ($css instanceof Css) {
                $finalCss->append($css);
            } elseif ($css instanceof File) {
                $finalCss->append($css->readFileContent());
            }
        }

        return $finalCss;
    }

    // ============================================================
    // Fluent build methods
    // ============================================================

    // TODO: overrideConfig() overrideOptions()
    public function setConfig(Config $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function addTable(Table|array $table): static
    {
        $this->tables[] = $table instanceof Table ? $table : Table::fromArray($table);

        return $this;
    }

    public function addTablePath(TablePath|string $tablePath): static
    {
        $this->tables[] = match (true) {
            $tablePath instanceof TablePath => $tablePath,
            is_string($tablePath) => TablePath::fromPath($tablePath),
        };

        return $this;
    }

    public function setTemplate(Html|string $template): static
    {
        $this->template = $template instanceof Html ? $template : Html::fromString($template);

        return $this;
    }

    public function setTemplatePath(File|string $templateFile): static
    {
        $templateFile = $templateFile instanceof File ? $templateFile : app()->make(File::class, ['path' => $templateFile]);

        $templateFile->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('html');

        $this->template = $templateFile;

        return $this;
    }

    public function addCss(Css|string $css): static
    {
        $this->css[] = $css instanceof Css ? $css : Css::fromString($css);

        return $this;
    }

    public function addCssPath(File|string $cssFile): static
    {
        $cssFile = $cssFile instanceof File ? $cssFile : app()->make(File::class, ['path' => $cssFile]);

        $cssFile->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('css');

        $this->css[] = $cssFile;

        return $this;
    }

    public function tapOptions(callable $callback): static
    {
        $callback($this->options);

        return $this;
    }

    public function tapConfig(callable $callback): static
    {
        $callback($this->config);

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

    public function setFromArray(array $params): static
    {
        foreach ($params as $key => $value) {
            match ($key) {
                'template' => $this->setTemplate($value),
                'templatePath', 'template_path' => $this->setTemplatePath($value),
                'table' => $this->addTable($value),
                'tablePath', 'table_path' => $this->addTablePath($value),
                'css' => $this->addCss($value),
                'cssPath', 'css_path' => $this->addCssPath($value),
                // ? add many (tables, tablePaths, css, cssPaths)
                'interpolator' => $this->setInterpolator($value),
                'renderer' => $this->setRenderer($value),
                'options' => $this->options->setFromArray($value),
                default => null,
            };
        }

        return $this;
    }
}
