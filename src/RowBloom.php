<?php

namespace RowBloom\RowBloom;

use RowBloom\RowBloom\DataLoaders\DataLoaderFactory;
use RowBloom\RowBloom\Drivers\InterpolatorContract;
use RowBloom\RowBloom\Drivers\RendererContract;
use RowBloom\RowBloom\Fs\File;
use RowBloom\RowBloom\Interpolators\InterpolatorFactory;
use RowBloom\RowBloom\Renderers\RendererFactory;
use RowBloom\RowBloom\Renderers\Sizing\PaperFormat;
use RowBloom\RowBloom\Types\Css;
use RowBloom\RowBloom\Types\Html;
use RowBloom\RowBloom\Types\Table;

class RowBloom
{
    private InterpolatorContract|string $interpolator;

    private RendererContract|string $renderer;

    /** @var (Table|array)[] */
    private array $tables = [];

    private Html|File|null $template = null;

    /** @var (Css|File)[] */
    private array $css = [];

    // ------------------------------------------------------------

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

    private function table(): Table
    {
        if (empty($this->tables)) {
            throw new RowBloomException('A table is required');
        }

        $finalTable = Table::fromArray([]);

        foreach ($this->tables as $table) {
            if ($table instanceof Table) {
                $finalTable->append($table);
            } else {
                $finalTable->append($this->tableFromPath($table));
            }
        }

        return $finalTable;
    }

    private function tableFromPath(array $tablePath): Table
    {
        $DataLoaderFactory = app()->make(DataLoaderFactory::class);

        $DataLoader = null;

        if (isset($tablePath['driver'])) {
            $DataLoader = $DataLoaderFactory->make($tablePath['driver']);
        } else {
            $DataLoader = $DataLoaderFactory->makeFromPath($tablePath['path']);
        }

        return $DataLoader->getTable($tablePath['path']);
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

    public function addTablePath(string $tablePath, string $driver = null): static
    {
        // ? improve type (TablePath...)
        $this->tables[] = [
            'path' => $tablePath,
            'driver' => $driver,
        ];

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

    // ------------------------------------------------------------
    // Options setters
    // ------------------------------------------------------------

    public function setOption(string $key, mixed $value): static
    {
        $this->options->$key = $value;

        return $this;
    }

    public function setDisplayHeaderFooterOption(bool $value): static
    {
        $this->options->displayHeaderFooter = $value;

        return $this;
    }

    public function setRawHeaderOption(?string $value): static
    {
        $this->options->rawHeader = $value;

        return $this;
    }

    public function setRawFooterOption(?string $value): static
    {
        $this->options->rawFooter = $value;

        return $this;
    }

    public function setPrintBackgroundOption(bool $value): static
    {
        $this->options->printBackground = $value;

        return $this;
    }

    public function setPreferCSSPageSizeOption(bool $value): static
    {
        $this->options->preferCSSPageSize = $value;

        return $this;
    }

    public function setPerPageOption(?int $value): static
    {
        $this->options->perPage = $value;

        return $this;
    }

    public function setLandscapeOption(bool $value): static
    {
        $this->options->landscape = $value;

        return $this;
    }

    public function setFormatOption(?PaperFormat $value): static
    {
        $this->options->format = $value;

        return $this;
    }

    public function setWidthOption(?string $value): static
    {
        $this->options->width = $value;

        return $this;
    }

    public function setHeightOption(?string $value): static
    {
        $this->options->height = $value;

        return $this;
    }

    public function setMarginOption(array|string $value): static
    {
        $this->options->margin = $value;

        return $this;
    }

    public function setMetadataTitleOption(?string $value): static
    {
        $this->options->metadataTitle = $value;

        return $this;
    }

    public function setMetadataAuthorOption(?string $value): static
    {
        $this->options->metadataAuthor = $value;

        return $this;
    }

    public function setMetadataCreatorOption(?string $value): static
    {
        $this->options->metadataCreator = $value;

        return $this;
    }

    public function setMetadataSubjectOption(?string $value): static
    {
        $this->options->metadataSubject = $value;

        return $this;
    }

    public function setMetadataKeywordsOption(?string $value): static
    {
        $this->options->metadataKeywords = $value;

        return $this;
    }

    // ============================================================
    //
    // ============================================================

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
}
