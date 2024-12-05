<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;
use App\MoonShine\Pages\Employee\EmployeeIndexPage;
use App\MoonShine\Pages\Employee\EmployeeFormPage;
use App\MoonShine\Pages\Employee\EmployeeDetailPage;
use MoonShine\Attributes\Icon;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\ID;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Text;
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

    public function fields(): array
    {
        return [
            Text::make('Nombres', 'first_name')->showOnExport()->useOnImport(),
            Text::make('Apellidos', 'last_name')->showOnExport()->useOnImport(),
            Email::make('Correo', 'email')->showOnExport()->useOnImport(),
            Date::make('Fecha ingreso', 'hire_date')->showOnExport()->useOnImport(),
            BelongsTo::make('Departamento', 'department', resource: new DepartmentResource())->showOnExport()->useOnImport()
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
            'email' => 'required|string|email|max:150|unique:table_name,email',
            'hire_date' => 'required|date|before_or_equal:today',
            'department_id' => 'required|integer|exists:departments,id',
        ];
    }
}
