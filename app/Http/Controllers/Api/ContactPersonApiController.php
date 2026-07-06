<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactPerson;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactPersonApiController extends Controller
{
    public function sync(Request $request, string $employer): Response
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'uuid'],
            'contact_persons' => ['present', 'array'],
            'contact_persons.*.id' => ['required', 'uuid'],
            'contact_persons.*.name' => ['required', 'string'],
            'contact_persons.*.email' => ['nullable', 'email'],
            'contact_persons.*.phone' => ['nullable', 'string'],
            'contact_persons.*.job_title' => ['nullable', 'string'],
        ]);

        Employer::query()->where('tenant_id', $data['tenant_id'])->findOrFail($employer);

        $incomingIds = array_column($data['contact_persons'], 'id');

        foreach ($data['contact_persons'] as $person) {
            ContactPerson::query()->updateOrCreate(
                ['id' => $person['id']],
                [
                    'employer_id' => $employer,
                    'name' => $person['name'],
                    'email' => $person['email'] ?? null,
                    'phone' => $person['phone'] ?? null,
                    'job_title' => $person['job_title'] ?? null,
                ],
            );
        }

        ContactPerson::query()
            ->where('employer_id', $employer)
            ->whereNotIn('id', $incomingIds)
            ->delete();

        return response()->noContent();
    }
}
