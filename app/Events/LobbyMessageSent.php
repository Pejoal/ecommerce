<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LobbyMessageSent implements ShouldBroadcastNow {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $user;
  public $content;
  public $lobbyId;
  public function __construct(User $user, string $content, int $lobbyId) {
    $this->user = $user;
    $this->content = $content;
    $this->lobbyId = $lobbyId;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, \Illuminate\Broadcasting\Channel>
   */
  public function broadcastOn(): array
  {
    return [
      new PresenceChannel('chat.' . $this->lobbyId),
    ];
  }

  public function broadcastWith(): array
  {
    return [
      "content" => $this->content,
      "user_id" => $this->user->id,
      "firstname" => $this->user->firstname,
      "lastname" => $this->user->lastname,
    ];
  }
}
