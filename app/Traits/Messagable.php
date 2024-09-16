<?php

namespace App\Traits;

use App\Interfaces\HasMessages;
use App\Models\Conversation;
use App\Models\Message;
use App\Relations\HasConversations;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait Messagable
{
    use HasConversations;

    /**
     * Send a message to another model.
     */
    public function sendMessage(Model $to, string|UploadedFile $content): array
    {
        try {
            $conversation = $this->findOrCreateConversation($to);
            $message = null;
            DB::transaction(function () use ($to, $conversation, $content, &$message) {
                $message = $conversation->messages()->create([
                    'content' => $content instanceof UploadedFile ? '📎 File' : $content,
                    'sender_type' => get_class($this),
                    'sender_id' => $this->id
                ]);
                if ($content instanceof UploadedFile) {
                    $message->addMedia($content)
                        ->toMediaCollection(Message::MEDIA_COLLECTION);
                }

                $conversation->update([
                    'last_message_at' => $message->created_at,
                    'last_message' => $message->content,
                    'is_read' => false
                ]);

                Notification::make()
                    ->title('New message from ' . $this->name)
                    ->body($message->content)
                    ->success()
                    ->sendToDatabase($to);
            });

            $response = [
                'response' => 'Message sent successfully',
                'message' => $message
            ];
        } catch (Exception $e) {
            Log::error('Message sending failed: ' . $e->getMessage());
            $response = [
                'response' => $e->getMessage(),
                'message' => null
            ];
        }
        return $response;
    }

    /**
     * @throws Exception
     */
    public function findOrCreateConversation(Model $to): Conversation
    {
        if ((!$to instanceof HasMessages) || (!$this instanceof HasMessages)) {
            throw new Exception("The given model doesn't implement HasMessages interface");
        }
        $conversation = $this->conversations()
            ->whereHasMorph(
                relation: 'receiver',
                types: [get_class($to)],
                callback: function (Builder $query) use ($to) {
                    $query->where(['receiver_id' => $to->id]);
                })
            ->orWhere(function (Builder $query) use ($to) {
                $query->whereHasMorph(
                    relation: 'sender',
                    types: [get_class($to)],
                    callback: function (Builder $subQuery) use ($to) {
                        $subQuery->where(['sender_id' => $to->id]);
                    });
            })
            ->first();

        if (!$conversation) {
            $conversation = $this->conversations()->create([
                'sender_type' => get_class($this),
                'sender_id' => $this->id,
                'receiver_type' => get_class($to),
                'receiver_id' => $to->id,
            ]);
            $conversation->receiver()->associate($to);
            $conversation->save();
        }
        return $conversation;
    }
}
