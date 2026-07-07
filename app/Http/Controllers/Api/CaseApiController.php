<?php

namespace App\Http\Controllers\Api;

use App\Enums\CaseType;
use App\Http\Controllers\Controller;
use App\Models\CaseFile;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CaseApiController extends Controller
{
    public function sync(Request $request, string $id): Response
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'uuid'],
            'employee_id' => ['required', 'uuid'],
            'case_type' => ['nullable', Rule::enum(CaseType::class)],
            'status' => ['required', 'string'],
            'opened_at' => ['required', 'date'],
            'expected_return_date' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date'],
        ]);

        $employee = Employee::query()
            ->whereHas('employer', fn ($q) => $q->where('tenant_id', $data['tenant_id']))
            ->findOrFail((string) $data['employee_id']);

        CaseFile::query()->updateOrCreate(
            ['id' => $id],
            [
                ...$data,
                'employer_id' => $employee->employer_id,
                'case_type' => $data['case_type'] ?? 'verzuim',
            ],
        );

        return response()->noContent();
    }
}
