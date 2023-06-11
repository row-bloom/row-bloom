<?php

namespace ElaborateCode\RowBloom;

use ElaborateCode\RowBloom\DataCollectors\DataCollectorFactory;
use ElaborateCode\RowBloom\Fs\File;
use ElaborateCode\RowBloom\Interpolators\InterpolatorFactory;
use ElaborateCode\RowBloom\Renderers\RendererFactory;
use ElaborateCode\RowBloom\Types\Css;
use ElaborateCode\RowBloom\Types\Table;
use ElaborateCode\RowBloom\Types\Template;

class RowBloom
{
    protected DataCollectorContract $dataCollector;

    protected InterpolatorContract $interpolator;
    // protected InterpolatorContract $renderer;

    protected Template $template;

    protected Css $css;

    protected Table $table; // support calculations

    // TODO: $options

    // use pending setter when path given to use after the build ends

    public function __construct()
    {
        // handle config, options, and defaults (drivers ...)
        // any options ?
        //      rows_per_page, output_name

        $this->dataCollector = DataCollectorFactory::make();
        $this->interpolator = InterpolatorFactory::make('');
        // $this->renderer = RendererFactory::make();
    }

    public function template(string|Template $content): static
    {
        $this->template = $content instanceof Template ? $content : new Template($content);

        return $this;
    }

    public function css(string|Css $content): static
    {
        $this->css = $content instanceof Css ? $content : new Css($content);

        return $this;
    }

    public function table(array|Table $content): static
    {
        $this->table = $content instanceof Table ? $content : new Table($content);

        return $this;
    }

    public function templateFromFile(string|File $file): static
    {
        $file = $file instanceof File ? $file : new File($file);

        $file->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('html');

        return $this->template(
            new Template($file->readFileContent())
        );
    }

    public function cssFromFile(string|File $file): static
    {
        $file = $file instanceof File ? $file : new File($file);

        $file->mustExist()->mustBeReadable()->mustBeFile()->mustBeExtension('css');

        return $this->css(
            new Css($file->readFileContent())
        );
    }

    public function tableFrom(string $path): static
    {
        $this->table(
            $this->dataCollector->getTable($path)
        );

        return $this;
    }

    public function render()
    {
        $interpolatedTemplate = $this->interpolator->interpolate(
            $this->template,
            $this->table
        );

        return RendererFactory::make('html', $interpolatedTemplate, $this->css)
            ->getRendering();
    }

    // save()
    // stream
}
