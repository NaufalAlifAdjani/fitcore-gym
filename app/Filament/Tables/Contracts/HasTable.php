<?php

declare(strict_types=1);

namespace App\Filament\Tables\Contracts;

use App\Filament\Tables\Table;

interface HasTable
{
    public function table(Table $table): Table;
}

if (! interface_exists('Filament\Tables\Contracts\HasTable', false)) {
    class_alias(HasTable::class, 'Filament\Tables\Contracts\HasTable');
}
