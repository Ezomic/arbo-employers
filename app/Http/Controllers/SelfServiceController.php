<?php

namespace App\Http\Controllers;

use App\Models\CaseFile;
use App\Models\Employee;
use Carbon\CarbonImmutable;
use Illuminate\Http\Response as DownloadResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SelfServiceController extends Controller
{
    public function show(): Response
    {
        $user = Auth::user();
        $employee = Employee::query()->findOrFail($user->employee_id);

        $cases = CaseFile::query()
            ->where('employee_id', $employee->id)
            ->orderByDesc('opened_at')
            ->get(['id', 'status', 'opened_at', 'closed_at', 'expected_return_date']);

        return Inertia::render('self-service/Show', [
            'employee' => [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
            ],
            'cases' => $cases->map(fn ($c) => [
                'id' => $c->id,
                'status' => $c->status,
                'opened_at' => $c->opened_at?->toDateString(),
                'closed_at' => $c->closed_at?->toDateString(),
                'expected_return_date' => $c->expected_return_date?->toDateString(),
            ]),
        ]);
    }

    public function gdprExport(): DownloadResponse
    {
        $user = Auth::user();
        $employee = Employee::query()->findOrFail($user->employee_id);

        $cases = CaseFile::query()
            ->where('employee_id', $employee->id)
            ->orderByDesc('opened_at')
            ->get();

        $payload = [
            'export_generated_at' => CarbonImmutable::now()->toIso8601String(),
            'legal_basis' => 'AVG Art. 15 (recht op inzage) / Art. 20 (dataportabiliteit) / WGBO Art. 456',
            'employee' => [
                'id' => $employee->id,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'email' => $employee->email,
                'employee_number' => $employee->employee_number,
            ],
            'cases' => $cases->map(fn ($c) => [
                'id' => $c->id,
                'status' => $c->status,
                'opened_at' => $c->opened_at?->toDateString(),
                'closed_at' => $c->closed_at?->toDateString(),
                'expected_return_date' => $c->expected_return_date?->toDateString(),
            ])->all(),
            'note' => 'Medical notes and diagnoses are held by your occupational health physician and are not included in this export per WGBO Art. 456.',
        ];

        $filename = sprintf(
            'gdpr-export-%s-%s.json',
            strtolower($employee->last_name),
            now()->format('Y-m-d'),
        );

        return response(
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            200,
            [
                'Content-Type' => 'application/json',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ],
        );
    }
}
