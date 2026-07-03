<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CaseOfficersClient;
use App\Services\EmployerSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployeeImportController extends Controller
{
    /**
     * Uploads always go through Case Officers' API — this app never
     * parses the file itself, it just proxies the upload to the
     * canonical owner and polls for the result.
     */
    public function store(Request $request, CaseOfficersClient $client): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt,xlsx,xml']]);

        /** @var User $user */
        $user = $request->user();

        $import = $client->importEmployees($user->tenant_id, $user->employer_id, $user->id, $request->file('file'));

        return to_route('employer.show')->with('importId', $import['id']);
    }

    public function status(Request $request, int $import, CaseOfficersClient $client, EmployerSyncService $sync): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $status = $client->getImportStatus($user->tenant_id, $import);

        if ($status['status'] === 'completed') {
            $sync->sync($user->tenant_id, $user->employer_id);
        }

        return response()->json($status);
    }
}
