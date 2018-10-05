<?php
/**
 * Yasmin
 * Copyright 2017-2018 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Yasmin\WebSocket\Events;

/**
 * WS Event
 * @see https://discordapp.com/developers/docs/topics/gateway#channel-delete
 * @internal
 */
class ChannelDelete implements \CharlotteDunois\Yasmin\Interfaces\WSEventInterface {
    /**
     * The client.
     * @var \CharlotteDunois\Yasmin\Client
     */
    protected $client;
    
    function __construct(\CharlotteDunois\Yasmin\Client $client, \CharlotteDunois\Yasmin\WebSocket\WSManager $wsmanager) {
        $this->client = $client;
    }
    
    function handle(\CharlotteDunois\Yasmin\WebSocket\WSConnection $ws, array $data): void {
        $channel = $this->client->channels->get($data['id']);
        if($channel instanceof \CharlotteDunois\Yasmin\Interfaces\ChannelInterface) {
            if($channel instanceof \CharlotteDunois\Yasmin\Interfaces\GuildChannelInterface) {
                $channel->getGuild()->channels->delete($channel->getId());
            }
            
            $this->client->channels->delete($channel->getId());
            $this->client->queuedEmit('channelDelete', $channel);
        }
    }
}
