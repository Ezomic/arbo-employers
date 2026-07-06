<?php

namespace App\Http\Middleware;

use App\Enums\CaseType;
use App\Models\CaseFile;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'caseTypes' => array_values(array_map(
                fn (CaseType $type) => ['value' => $type->value, 'label' => $type->label()],
                array_filter(CaseType::cases(), fn (CaseType $type) => $type->employerVisible()),
            )),
            'sidebarOpenCases' => function () use ($request) {
                $user = $request->user();
                if (! $user?->tenant_id || ! $user->employer_id) {
                    return [];
                }

                return CaseFile::query()
                    ->where('tenant_id', $user->tenant_id)
                    ->where('employer_id', $user->employer_id)
                    ->where('status', 'open')
                    ->with('employee:id,first_name,last_name')
                    ->oldest('opened_at')
                    ->get(['id', 'employee_id', 'expected_return_date'])
                    ->map(fn (CaseFile $case) => [
                        'id' => $case->id,
                        'employee' => $case->employee
                            ? ['first_name' => $case->employee->first_name, 'last_name' => $case->employee->last_name]
                            : null,
                        'expected_return_date' => $case->expected_return_date?->toDateString(),
                    ])
                    ->all();
            },
            'flash' => [
                'temporaryPassword' => fn () => $request->session()->get('temporaryPassword'),
            ],
        ];
    }
}
