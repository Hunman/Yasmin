<?php
/**
 * Yasmin
 * Copyright 2017 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Yasmin\WebSocket\Events;

/**
 * WS Event
 * @see https://discordapp.com/developers/docs/topics/gateway#guild-update
 * @access private
 */
class GuildUpdate {
    protected $client;
    protected $clones = false;
    
    function __construct(\CharlotteDunois\Yasmin\Client $client) {
        $this->client = $client;
        
        $clones = (array) $this->client->getOption('disableClones', array());
        $this->clones = !\in_array('messageUpdate', $clones);
    }
    
    function handle(array $data) {
        $guild = $this->client->guilds->get($data['id']);
        if($guild) {
            $oldGuild = null;
            if($this->clones) {
                $oldGuild = clone $guild;
            }
            
            $guild->_patch($data);
            
            $this->client->emit('guildUpdate', $guild, $oldGuild);
        }
    }
}
