<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataLoaders\Factory as DataLoadersFactory;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Interpolators\Contract as InterpolatorsContract;
use RowBloom\RowBloom\Interpolators\Factory as InterpolatorsFactory;
use RowBloom\RowBloom\Renderers\Contract as RenderersContract;
use RowBloom\RowBloom\Renderers\Factory as RenderersFactory;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;
use RowBloom\RowBloom\Types\TableLocation;

class RowBloom
{
    private InterpolatorsContract|string $interpolator;

    private RenderersContract|string $renderer;

    /** @var (Table|TableLocation)[] */
    private array $tables = [];

    private Html|File|null $template = null;

    /** @var (Css|File)[] */
    private array $css = [];

    // ------------------------------------------------------------

    public function __construct(
        private Options $options,
        private Config $config,
        private InterpolatorsFactory $interpolatorFactory,
        private RenderersFactory $rendererFactory,
        private DataLoadersFactory $dataLoaderFactory,
    ) {
    }

    public function save(File|string $file): bool
    {
        $file = $file instanceof File ? $file : File::fromPath($file);

        return $this->render()->save($file);
    }

    public function get(): string
    {
        return $this->render()->get();
    }

    private function render(): RenderersContract
    {
        $interpolator = $this->resolveInterpolator();
        $renderer = $this->resolveRenderer();

        $finalTable = $this->table();
        $finaleTemplate = $this->template();
        $finalCss = $this->css();

        $interpolatedTemplate = $interpolator->interpolate($finaleTemplate, $finalTable, $this->options->perPage, $this->config);

        return $renderer->render($interpolatedTemplate, $finalCss, $this->options, $this->config);
    }

    // ------------------------------------------------------------

    private function resolveInterpolator(): InterpolatorsContract
    {
        if (! isset($this->interpolator)) {
            throw new RowBloomException('Interpolator must be set');
        }

        if ($this->interpolator instanceof InterpolatorsContract) {
            return $this->interpolator;
        }

        return $this->interpolatorFactory->make($this->interpolator);
    }

    private function resolveRenderer(): RenderersContract
    {
        if (! isset($this->renderer)) {
            throw new RowBloomException('Renderer must be set');
        }

        if ($this->renderer instanceof RenderersContract) {
            return $this->renderer;
        }

        return $this->rendererFactory->make($this->renderer);
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
                $table instanceof TableLocation => $this->tableFromLocation($table),
            });
        }

        return $finalTable;
    }

    private function tableFromLocation(TableLocation $tableLocation): Table
    {
        $dataLoader = null;

        if ($tableLocation->driver) {
            $dataLoader = $this->dataLoaderFactory->make($tableLocation->driver);
        } else {
            $dataLoader = $this->dataLoaderFactory->makeFromLocation($tableLocation);
        }

        return $dataLoader->getTable($tableLocation, $this->config);
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

        if (! is_null($this->config->baseCss)) {
            $baseCss = File::fromPath($this->config->baseCss->value)
                ->readFileContent();

            $finalCss->append($baseCss);
        }

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

    public function overrideConfig(Config $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function overrideOptions(Options $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function addTable(Table|array $table): static
    {
        $this->tables[] = $table instanceof Table ? $table : Table::fromArray($table);

        return $this;
    }

    public function addTableLocation(TableLocation|string $tableLocation): static
    {
        $this->tables[] = match (true) {
            $tableLocation instanceof TableLocation => $tableLocation,
            is_string($tableLocation) => TableLocation::make($tableLocation),
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
        $templateFile = $templateFile instanceof File ? $templateFile : File::fromPath($templateFile);

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
        $cssFile = $cssFile instanceof File ? $cssFile : File::fromPath($cssFile);

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

    public function setInterpolator(InterpolatorsContract|string $interpolator): static
    {
        $this->interpolator = $interpolator;

        return $this;
    }

    public function setRenderer(RenderersContract|string $renderer): static
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
                'tableLocation', 'table_location' => $this->addTableLocation($value),
                'css' => $this->addCss($value),
                'cssPath', 'css_path' => $this->addCssPath($value),
                // ? add many (tables, tableLocations, css, cssPaths)
                'interpolator' => $this->setInterpolator($value),
                'renderer' => $this->setRenderer($value),
                'options' => $this->options->setFromArray($value),
                default => null,
            };
        }

        return $this;
    }
}
