<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\MoonShine\Pages\Employee\EmployeeIndexPage;
use App\MoonShine\Pages\Employee\EmployeeFormPage;
use App\MoonShine\Pages\Employee\EmployeeDetailPage;
use MoonShine\Attributes\Icon;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Field;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Resources\ModelResource;
use MoonShine\Pages\Page;

/**
 * @extends ModelResource<Employee>
 */
#[Icon('heroicons.user-group')]
class EmployeeResource extends ModelResource
{
    protected string $model = Employee::class;

    protected string $title = 'Employees';

    protected bool $columnSelection = true;

    protected string $sortColumn = 'id';

    protected string $sortDirection = 'DESC';

    protected int $itemsPerPage = 15;

    /**
     * @return list<Page>
     */
    public function pages(): array
    {
        return [
            EmployeeIndexPage::make($this->title()),
            EmployeeFormPage::make(
                $this->getItemID()
                    ? __('moonshine::ui.edit')
                    : __('moonshine::ui.add')
            ),
            EmployeeDetailPage::make(__('moonshine::ui.show')),
        ];
    }

    public function metrics(): array
    {
        return [
          ValueMetric::make('Empleados')
          ->value(Employee::count())
          ->progress(100)  
          ->icon('heroicons.user-group')
        ];
    }
    

    public function indexFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Nombres', 'first_name', fn($item) => $item->first_name . ' ' . $item->last_name)
                ->showOnExport()->useOnImport()->sortable(),
            Text::make('Apellidos', 'last_name')
                ->showOnExport()->useOnImport(),
            Email::make('Correo', 'email')
                ->showOnExport()->useOnImport(),
            Date::make('Fecha ingreso', 'hire_date')
                ->showOnExport()->useOnImport(),
            BelongsTo::make('Departamento', 'department', resource: new DepartmentResource())
                ->showOnExport()->useOnImport()
        ];
    }

    public function formFields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Text::make('Nombres', 'first_name'),
                    Text::make('Apellidos', 'last_name'),
                ])->columnSpan(6),

                Column::make([
                    Email::make('Correo', 'email'),
                    Date::make('Fecha ingreso', 'hire_date'),
                ])->columnSpan(6),
            ]),
            BelongsTo::make('Departamento', 'department', resource: new DepartmentResource())
        ];
    }

    public function detailFields(): array
    {
        return [
            Text::make('Nombres', 'first_name'),
            Text::make('Apellidos', 'last_name'),
            Email::make('Correo', 'email'),
            Date::make('Fecha ingreso', 'hire_date'),
            BelongsTo::make('Departamento', 'department', resource: new DepartmentResource())
        ];
    }

    public function filters(): array
    {
        return [
            BelongsTo::make('Departamento', 'department', resource: new DepartmentResource())
        ];
    }

    /**
     * @param Employee $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => request()->isMethod('post')
                ? 'required|string|email|max:150|unique:employees,email'
                : 'required|string|email|max:150|unique:employees,email,' . $item->id,
            'hire_date' => 'required|date|before_or_equal:today',
            'department_id' => 'required|integer|exists:departments,id',
        ];
    }
}
