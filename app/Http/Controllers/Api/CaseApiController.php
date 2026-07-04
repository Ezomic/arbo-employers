<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaseFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CaseApiController extends Controller
{
    public function sync(Request $request, string $id): Response
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'uuid'],
            'employee_id' => ['required', 'uuid'],
            'case_type' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'opened_at' => ['required', 'date'],
            'expected_return_date' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date'],
        ]);

        CaseFile::query()->updateOrCreate(
            ['id' => $id],
            $data,
        );

        return response()->noContent();
    }
}
