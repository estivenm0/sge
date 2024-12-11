<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Attributes\Icon;
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Text;

/**
 * @extends ModelResource<Department>
 */
#[Icon('heroicons.rectangle-stack')]
class DepartmentResource extends ModelResource
{
    protected string $model = Department::class;

    protected string $title = 'Departments';

    protected string $column = 'name';

    protected bool $createInModal = true;

    protected bool $editInModal = true;

    protected bool $detailInModal = true;

    protected bool $isAsync = false;
    
    protected bool $columnSelection = true;

    protected string $sortColumn = 'id'; 
 
    protected string $sortDirection = 'DESC'; 
 
    protected int $itemsPerPage = 10; 


    public function getBadge(): int
    {
        return Department::count();
    }
    
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
                Text::make('Nombre', 'name')
            ]),
        ];
    }

    public function getActiveActions(): array
    {
        return ['create', 'view', 'update', 'delete', 'massDelete'];
    }

    public function redirectAfterSave(): string
    {
        return $this->url();
    }

    public function redirectAfterDelete(): string
    {
        return $this->url();
    }

    public function search(): array
    {
        return ['id', 'name'];
    }


    /**
     * @param Department $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'name' => 'required|max:100'
        ];
    }
}
