<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

class Column
{
    protected string $name;

    protected ?string $label = null;

    protected bool $isSortable = false;

    protected bool $isSearchable = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label ?? ucwords(str_replace('_', ' ', $this->name));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function sortable(bool $sortable = true): static
    {
        $this->isSortable = $sortable;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->isSortable;
    }

    public function searchable(bool $searchable = true): static
    {
        $this->isSearchable = $searchable;

        return $this;
    }

    public function isSearchable(): bool
    {
        return $this->isSearchable;
    }
}
