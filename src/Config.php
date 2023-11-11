<?php

namespace RowBloom\RowBloom;

// ? leave chromePath
// ? validate when passed to RowBloom
// TODO: rename config property
class Config
{
    /** @param  array<class-string, object>  $configs*/
    public function __construct(protected array $configs = [])
    {
    }

    public function setConfig(object $config): static
    {
        $this->configs[get_class($config)] = $config;

        return $this;
    }

    /**
     * @template TClassName
     *
     * @param  class-string<TClassName>  $configClassName
     * @return ?TClassName
     */
    public function getConfig($configClassName): ?object
    {
        return $this->configs[$configClassName] ?? null;
    }

    /** @param  class-string  $configClassName */
    public function mutateConfig($configClassName, callable $callback): static
    {
        if (! array_key_exists($configClassName, $this->configs)) {
            return $this;
        }

        $callback($this->configs[$configClassName]);

        return $this;
    }
}
