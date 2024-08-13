<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LobbyTurnChange implements ShouldBroadcastNow {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $lobbyId;
  public $cards;
  public $nextUser;

  public function __construct(int $lobbyId, array $cards,User $nextUser) {
    $this->lobbyId = $lobbyId;
    $this->cards = $cards;
    $this->nextUser = $nextUser;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, \Illuminate\Broadcasting\Channel>
   */
  public function broadcastOn(): array {
    return [
      new PresenceChannel('chat.' . $this->lobbyId),
    ];
  }

  public function broadcastWith(): array {
    return [
      "cards" => $this->cards,
      "nextUserId" => $this->nextUser->id,
      "nextUsername" => $this->nextUser->full_name,
    ];
  }
}
