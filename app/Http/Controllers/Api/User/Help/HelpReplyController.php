<?php

namespace App\Http\Controllers\Api\User\Help;

use App\Http\Controllers\Controller;
use App\Http\Requests\Help\StoreHelpReplyRequest;
use App\Http\Resources\Help\HelpReplyResource;
use App\Http\Traits\ApiPaginationFilters;
use App\Models\Help\HelpAsk;
use App\Models\Help\HelpReply;
use App\Models\Help\HelpReplyVote;
use App\Models\User\User;
use App\Services\Notifications\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HelpReplyController extends Controller
{
    use ApiPaginationFilters;

    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function index(Request $request, HelpAsk $ask): JsonResponse
    {
        $user = $request->user('sanctum');

        $query = HelpReply::query()
            ->forAsk($ask->id)
            ->with('user')
            ->orderByDesc('votes_count')
            ->orderByDesc('created_at');

        if ($user instanceof User) {
            $query->withCount(['votes as voted_by_me' => fn ($q) => $q->where('user_id', $user->id)]);
        }

        $paginator = $query->paginate($this->getPerPage($request));
        $paginator->through(fn (HelpReply $reply) => HelpReplyResource::make($reply)->resolve($request));

        return response()->json($paginator);
    }

    public function store(StoreHelpReplyRequest $request, HelpAsk $ask): JsonResponse
    {
        $this->authorize('create', [HelpReply::class, $ask]);

        $validated = $request->validated();
        $reply = HelpReply::create([
            'help_ask_id' => $ask->id,
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'body' => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        $ask->increment('replies_count');
        $ask->forceFill(['last_activity_at' => now()])->save();

        $actor = $request->user();
        if ($ask->user_id !== $actor->id) {
            $owner = User::query()->find($ask->user_id);
            if ($owner !== null) {
                $this->notificationService->notify($owner, [
                    'title' => __('notifications.help_reply_added_title'),
                    'body' => __('notifications.help_reply_added_body', ['name' => $actor->name]),
                    'target_type' => 'help',
                    'target_id' => $ask->id,
                ]);
            }
        }

        $reply->load('user');
        $reply->loadCount(['votes as voted_by_me' => fn ($q) => $q->where('user_id', $request->user()->id)]);

        return HelpReplyResource::make($reply)->response($request)->setStatusCode(201);
    }

    public function vote(Request $request, HelpReply $reply): JsonResponse
    {
        $this->authorize('vote', $reply);

        $vote = HelpReplyVote::query()
            ->where('help_reply_id', $reply->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($vote) {
            $vote->delete();
            if ($reply->votes_count > 0) {
                $reply->decrement('votes_count');
            }
        } else {
            HelpReplyVote::create([
                'help_reply_id' => $reply->id,
                'user_id' => $request->user()->id,
            ]);
            $reply->increment('votes_count');

            $actor = $request->user();
            if ($reply->user_id !== $actor->id) {
                $owner = User::query()->find($reply->user_id);
                if ($owner !== null) {
                    $this->notificationService->notify($owner, [
                        'title' => __('notifications.help_reply_voted_title'),
                        'body' => __('notifications.help_reply_voted_body', ['name' => $actor->name]),
                        'target_type' => 'help',
                        'target_id' => $reply->help_ask_id,
                    ]);
                }
            }
        }

        $reply->refresh();

        return response()->json([
            'voted' => $vote === null,
            'votes_count' => $reply->votes_count,
        ]);
    }

    public function destroy(Request $request, HelpReply $reply): JsonResponse
    {
        $this->authorize('delete', $reply);

        $ask = HelpAsk::query()->find($reply->help_ask_id);
        $reply->delete();

        if ($ask !== null && $ask->replies_count > 0) {
            $ask->decrement('replies_count');
        }

        return response()->json(null, 204);
    }
}
