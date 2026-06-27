<?php

namespace App\Http\Controllers\Api\Admin\Companion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Companion\AdminUpdateCompanionSettingsRequest;
use App\Http\Resources\Companion\CompanionSettingsResource;
use App\Models\Companion\CompanionSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanionSettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CompanionSetting::class);

        $settings = CompanionSetting::singleton();

        return CompanionSettingsResource::make($settings)->response($request);
    }

    public function update(AdminUpdateCompanionSettingsRequest $request): JsonResponse
    {
        $settings = CompanionSetting::singleton();

        $this->authorize('update', $settings);

        $settings->update($request->validated());

        return CompanionSettingsResource::make($settings->fresh())->response($request);
    }
}
