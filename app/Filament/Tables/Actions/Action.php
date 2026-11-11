<?php

declare(strict_types=1);

namespace App\Filament\Tables\Actions;

use Closure;

class Action
{
    protected string $name;

    protected ?string $label = null;

    protected ?string $color = null;

    protected ?string $icon = null;

    protected ?Closure $visibleCallback = null;

    protected ?Closure $actionCallback = null;

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

    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function icon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function visible(Closure|bool $condition): static
    {
        $this->visibleCallback = $condition instanceof Closure ? $condition : fn () => $condition;

        return $this;
    }

    public function isVisible(mixed $record = null): bool
    {
        if ($this->visibleCallback === null) {
            return true;
        }

        return (bool) call_user_func($this->visibleCallback, $record);
    }

    public function action(Closure $action): static
    {
        $this->actionCallback = $action;

        return $this;
    }

    public function call(mixed ...$args): mixed
    {
        if ($this->actionCallback !== null) {
            return call_user_func_array($this->actionCallback, $args);
        }

        return null;
    }
}

if (! class_exists('Filament\Tables\Actions\Action', false)) {
    class_alias(Action::class, 'Filament\Tables\Actions\Action');
}
