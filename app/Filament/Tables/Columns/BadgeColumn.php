<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

class BadgeColumn extends Column
{
    /**
     * @var array<string, mixed>
     */
    protected array $colors = [];

    /**
     * @param  array<string, mixed>  $colors
     */
    public function colors(array $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getColors(): array
    {
        return $this->colors;
    }
}

if (! class_exists('Filament\Tables\Columns\BadgeColumn', false)) {
    class_alias(BadgeColumn::class, 'Filament\Tables\Columns\BadgeColumn');
}
