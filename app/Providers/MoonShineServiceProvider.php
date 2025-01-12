<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Department;
use App\Models\Employee;
use App\MoonShine\Resources\DepartmentResource;
use App\MoonShine\Resources\EmployeeResource;
use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use App\MoonShine\Resources\MoonShineUserResource;
use App\MoonShine\Resources\MoonShineUserRoleResource;
use App\Policies\MoonshineUserPolicy;
use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\Menu\MenuElement;
use MoonShine\Pages\Page;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use MoonShine\Menu\MenuDivider;
use MoonShine\Models\MoonshineUser;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{

    public function boot(): void
    {
        parent::boot();
    }
    /**
     * @return list<ResourceContract>
     */
    protected function resources(): array
    {
        return [];
    }

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [];
    }

    /**
     * @return Closure|list<MenuElement>
     */
    protected function menu(): array
    {   
        return [
            MenuGroup::make(static fn() => __('moonshine::ui.resource.system'), [
                MenuItem::make('resource.admins_title',new MoonShineUserResource())
                ->translatable('moonshine::ui'),
                MenuItem::make('resource.role_title',new MoonShineUserRoleResource())
                ->translatable('moonshine::ui'),
            ])->icon('heroicons.cube')
            ->canSee(function(Request $request) {
                return $request->user('moonshine')?->isHavePermission(MoonshineUserResource::class, 'delete');
            }) ,

            MenuDivider::make(),
 
            MenuItem::make('Departamentos', new DepartmentResource()),

            MenuItem::make('Empleados', new EmployeeResource())
            ->badge(fn()=> Employee::count())


            // MenuItem::make('Documentation', 'https://moonshine-laravel.com/docs')
            //     ->badge(fn() => 'Check')
            //     ->blank(),
        ];
    }

    /**
     * @return Closure|array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [ 
            'colors' => [
                'primary' => '#13BEC4',
                'secondary' => '#01C376',
                'body' => 'rgb(27, 37, 59)',
                'dark' => [
                    'DEFAULT' => 'rgb(30, 31, 67)',
                    50 => '#13BEC4',
                    100 => '#13BEC4',
                    200 => '#13BEC4',
                    300 => 'rgb(53, 69, 103)',
                    400 => 'rgb(48, 61, 93)',
                    500 => 'rgb(41, 53, 82)',
                    600 => 'rgb(40, 51, 78)',
                    700 => 'rgb(39, 45, 69)',
                    800 => 'rgb(27, 37, 59)',
                    900 => 'rgb(15, 23, 42)',
                ],
 
                'success-bg' => '#06D455',
                'success-text' => 'rgb(255, 255, 255)',
                'warning-bg' => 'rgb(255, 220, 42)',
                'warning-text' => 'rgb(139, 116, 0)',
                'error-bg' => '#AE0334',
                'error-text' => 'rgb(255, 255, 255)',
                'info-bg' => 'rgb(0, 121, 255)',
                'info-text' => 'rgb(255, 255, 255)',
            ],
            'darkColors' => [
                'body' => 'rgb(27, 37, 59)',
                'success-bg' => '#06D455',
                'success-text' => 'rgb(178, 255, 178)',
                'warning-bg' => 'rgb(225, 169, 0)',
                'warning-text' => 'rgb(255, 255, 199)',
                'error-bg' => '#AE0334',
                'error-text' => 'rgb(255, 197, 197)',
                'info-bg' => 'rgb(38, 93, 205)',
                'info-text' => 'rgb(179, 220, 255)',
            ]
        ];
    }
}
