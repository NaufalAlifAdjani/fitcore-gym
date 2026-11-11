<?php

declare(strict_types=1);

namespace App\Filament\Tables\Concerns;

use App\Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

trait InteractsWithTable
{
    protected ?Table $tableInstance = null;

    public function getTable(): Table
    {
        if ($this->tableInstance !== null) {
            return $this->tableInstance;
        }

        $table = Table::make($this);

        return $this->tableInstance = $this->table($table);
    }

    public function getTableProperty(): Htmlable
    {
        return $this->getTable()->render();
    }
}

if (! trait_exists('Filament\Tables\Concerns\InteractsWithTable', false)) {
    class_alias(InteractsWithTable::class, 'Filament\Tables\Concerns\InteractsWithTable');
}
