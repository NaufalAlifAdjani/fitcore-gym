<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Livewire\Component;

abstract class Page extends Component
{
    protected static ?string $navigationIcon = null;

    protected static ?string $navigationLabel = null;

    protected static ?string $slug = null;

    protected static string $view = '';

    public static function getNavigationIcon(): ?string
    {
        return static::$navigationIcon;
    }

    public static function getNavigationLabel(): ?string
    {
        return static::$navigationLabel ?? class_basename(static::class);
    }

    public static function getSlug(): ?string
    {
        return static::$slug ?? strtolower(class_basename(static::class));
    }
}

if (! class_exists('Filament\Pages\Page', false)) {
    class_alias(Page::class, 'Filament\Pages\Page');
}
