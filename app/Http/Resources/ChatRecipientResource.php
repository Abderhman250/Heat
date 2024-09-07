<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatRecipientResource extends JsonResource
{
    public function toArray($request)
    {
        $chat = parent::toArray($request);
        $currentUser = Auth::user();
        $userCreatorId = $chat['message']['user_creator_id'] ?? null;
        $isMyMessage = $currentUser->id === $userCreatorId;

        return [
            "id" => $this->id,
            "message_id" => $this->message_id,
            "user_id" => $this->user_id,
            "is_read" => $this->is_read,
            "message_info" => [
                "user_creator_id" => $userCreatorId,
                "message_body" => $chat['message']['message_body'] ?? null,
                "parent_message_id" => $chat['message']['parent_message_id'] ?? null,
                "expiry_date" => $chat['message']['expiry_date'] ?? null,
                "is_reminder" => $chat['message']['is_reminder'] ?? null,
                "time_sent" => $chat['message']['created_at'] ?? null,
                "is_my_message" => $isMyMessage,
            ],
            "user_creator" => $this->transformUserCreator($chat['message']['user_creator'] ?? null),
            "parent_message"=>$this->transParentMessage($chat['message']['parent_message']?? null),
        ];
    }

    /**
     * Transform the user creator data.
     *
     * @param array|null $userCreator
     * @return array|null
     */
    private function transParentMessage($parentMessage)
    {
        if (!$parentMessage) 
            return null;
        
 
        $chat= $parentMessage;
        $currentUser = Auth::user();
        $userCreatorId = $chat['user_creator_id'] ?? null;
        $isMyMessage = $currentUser->id === $userCreatorId;
        return [
    
            "message_info" => [
                "id" => $parentMessage['id'],
                "user_creator_id" => $userCreatorId,
                "message_body" => $chat['message_body'] ?? null,
                "parent_message_id" => $chat['parent_message_id'] ?? null,
                "expiry_date" => $chat['expiry_date'] ?? null,
                "is_reminder" => $chat['is_reminder'] ?? null,
                "time_sent" => $chat['created_at'] ?? null,
                "is_my_message" => $isMyMessage,
            ],
            "user_creator" => $this->transformUserCreator($chat['user_creator'] ?? null),
         ];
    }

    /**
     * Transform the user creator data.
     *
     * @param array|null $userCreator
     * @return array|null
     */
    private function transformUserCreator($userCreator)
    {
        if (!$userCreator) {
            return null;
        }

        return [
            "id" => $userCreator['id'] ?? null,
            "name" => $userCreator['name'] ?? null,
            "username" => $userCreator['username'] ?? null,
            "email" => $userCreator['email'] ?? null,
            "phone_number" => $userCreator['phone_number'] ?? null,
            "is_blocked" => $userCreator['is_blocked'] ?? null,
        ];
    }

}
