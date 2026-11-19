<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\Tables\Actions\Action;
use App\Filament\Tables\Columns\Column;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;

class Table implements Htmlable
{
    protected mixed $livewire = null;

    protected ?Builder $query = null;

    /**
     * @var array<int, Column>
     */
    protected array $columns = [];

    /**
     * @var array<int, Action>
     */
    protected array $actions = [];

    public function __construct(mixed $livewire = null)
    {
        $this->livewire = $livewire;
    }

    public static function make(mixed $livewire = null): static
    {
        return new static($livewire);
    }

    public function query(Builder $query): static
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery(): ?Builder
    {
        return $this->query;
    }

    /**
     * @param  array<int, Column>  $columns
     */
    public function columns(array $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return array<int, Column>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param  array<int, Action>  $actions
     */
    public function actions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return array<int, Action>
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function getLivewire(): mixed
    {
        return $this->livewire;
    }

    public function getRecords(): LengthAwarePaginator|iterable
    {
        if ($this->query !== null) {
            return $this->query->paginate(15);
        }

        return collect();
    }

    public function render(): HtmlString
    {
        $records = $this->getRecords();

        $html = View::make('admin.settings.table', [
            'table' => $this,
            'records' => $records,
            'columns' => $this->columns,
            'actions' => $this->actions,
            'livewire' => $this->livewire,
        ])->render();

        return new HtmlString($html);
    }

    public function toHtml(): string
    {
        return (string) $this->render();
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }
}

if (! class_exists('Filament\Tables\Table', false)) {
    class_alias(Table::class, 'Filament\Tables\Table');
}
