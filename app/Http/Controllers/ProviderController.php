<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    /**
     * GET /api/provider - returns current user's provider info or 404.
     */
    public function show()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $provider = $user->provider;
        if (!$provider) {
            return response()->json(['message' => 'Provider not found'], 404);
        }
        return response()->json($provider);
    }

    /**
     * POST /api/provider - create or update provider for current user.
     */
    public function upsert(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'legal_representative' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'folio' => ['nullable', 'string', 'max:255'],
            'contract_number' => ['nullable', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $provider = $user->provider;
        if ($provider) {
            $provider->update($data);
        } else {
            $provider = Provider::create(array_merge($data, ['user_id' => $user->id]));
        }

        return response()->json($provider);
    }
}
