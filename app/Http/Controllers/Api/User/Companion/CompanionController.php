<?php

namespace App\Http\Controllers\Api\User\Companion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Companion\StoreCompanionMessageRequest;
use App\Http\Resources\Companion\CompanionConversationResource;
use App\Http\Resources\Companion\CompanionMessageResource;
use App\Http\Resources\Companion\CompanionSettingsResource;
use App\Http\Resources\Companion\CompanionSuggestionResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Companion\CompanionConversation;
use App\Models\Companion\CompanionMessage;
use App\Models\Companion\CompanionSetting;
use App\Models\Companion\CompanionSuggestion;
use App\Services\Companion\CompanionReplyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanionController extends Controller
{
    use ApiPaginationFilters;

    public function __construct(private readonly CompanionReplyService $replyService) {}

    /**
     * The caller's active conversation. On first access a greeting cat message is seeded.
     */
    private function activeConversation(Request $request): CompanionConversation
    {
        $user = $request->user();

        $conversation = CompanionConversation::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->first();

        if ($conversation === null) {
            $conversation = CompanionConversation::create([
                'user_id' => $user->id,
            ]);

            $greeting = $this->replyService->greeting();
            if ($greeting !== null) {
                CompanionMessage::create([
                    'companion_conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                    'role' => CompanionMessage::ROLE_CAT,
                    'body' => $greeting->text,
                ]);
                $conversation->forceFill([
                    'message_count' => 1,
                    'last_message_at' => now(),
                ])->save();
            }
        }

        return $conversation;
    }

    public function conversation(Request $request): JsonResponse
    {
        $conversation = $this->activeConversation($request);

        return CompanionConversationResource::make($conversation)->response($request);
    }

    public function messagesIndex(Request $request): JsonResponse
    {
        $conversation = $this->activeConversation($request);

        $paginator = CompanionMessage::query()
            ->forConversation($conversation->id)
            ->orderByDesc('created_at')
            ->paginate($this->getPerPage($request));

        $paginator->through(fn (CompanionMessage $m) => CompanionMessageResource::make($m)->resolve($request));

        return response()->json($paginator);
    }

    public function messagesStore(StoreCompanionMessageRequest $request): JsonResponse
    {
        $conversation = $this->activeConversation($request);
        $user = $request->user();

        CompanionMessage::create([
            'companion_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'role' => CompanionMessage::ROLE_USER,
            'body' => $request->validated('body'),
        ]);

        $reply = $this->replyService->randomReply();
        $catBody = $reply?->text ?? 'سمعتك يا صاحبي 🤍 وكل اللي بتحسّه طبيعي. عايز تحكيلي أكتر؟';

        $catMessage = CompanionMessage::create([
            'companion_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'role' => CompanionMessage::ROLE_CAT,
            'body' => $catBody,
        ]);

        $conversation->forceFill([
            'message_count' => $conversation->message_count + 2,
            'last_message_at' => now(),
        ])->save();

        return CompanionMessageResource::make($catMessage)->response($request)->setStatusCode(201);
    }

    public function suggestions(Request $request): JsonResponse
    {
        $suggestions = CompanionSuggestion::query()
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'data' => CompanionSuggestionResource::collection($suggestions)->resolve($request),
        ]);
    }

    public function config(Request $request): JsonResponse
    {
        $settings = CompanionSetting::singleton();

        return CompanionSettingsResource::make($settings)->response($request);
    }
}
