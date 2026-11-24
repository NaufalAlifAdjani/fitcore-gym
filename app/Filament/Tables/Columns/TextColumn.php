<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

class TextColumn extends Column
{
    protected ?string $currency = null;

    protected bool $isMoney = false;

    public function money(string $currency = 'IDR'): static
    {
        $this->isMoney = true;
        $this->currency = $currency;

        return $this;
    }

    public function isMoney(): bool
    {
        return $this->isMoney;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }
}

if (! class_exists('Filament\Tables\Columns\TextColumn', false)) {
    class_alias(TextColumn::class, 'Filament\Tables\Columns\TextColumn');
}
