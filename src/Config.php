<?php

namespace ElaborateCode\RowBloom;

class Config
{
    private ?string $nodeBinaryPath = null;

    private ?string $npmBinaryPath = null;

    private ?string $nodeModulesPath = null;

    private ?string $chromePath = null;

    public function getNodeBinaryPath(): ?string
    {
        return $this->nodeBinaryPath;
    }

    public function setNodeBinaryPath(?string $nodeBinaryPath): static
    {
        $this->nodeBinaryPath = $nodeBinaryPath;

        return $this;
    }

    public function getNpmBinaryPath(): ?string
    {
        return $this->npmBinaryPath;
    }

    public function setNpmBinaryPath(?string $npmBinaryPath): static
    {
        $this->npmBinaryPath = $npmBinaryPath;

        return $this;
    }

    public function getNodeModulesPath(): ?string
    {
        return $this->nodeModulesPath;
    }

    public function setNodeModulesPath(?string $nodeModulesPath): static
    {
        $this->nodeModulesPath = $nodeModulesPath;

        return $this;
    }

    public function getChromePath(): ?string
    {
        return $this->chromePath;
    }

    public function setChromePath(?string $chromePath): static
    {
        $this->chromePath = $chromePath;

        return $this;
    }
}
