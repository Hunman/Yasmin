<?php
/**
 * Yasmin
 * Copyright 2017-2018 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Yasmin\Models;

/**
 * Guild Member Storage to store guild members, utilizes Collection.
 */
class GuildMemberStorage extends Storage implements \CharlotteDunois\Yasmin\Interfaces\GuildMemberStorageInterface {
    /**
     * The guild ID this storage belongs to.
     * @var string
     */
    protected $guildID;
    
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Yasmin\Client $client, \CharlotteDunois\Yasmin\Models\Guild $guild, ?array $data = null) {
        parent::__construct($client, $data);
        $this->guildID = $guild->id;
    }
    
    /**
     * Resolves given data to a guildmember.
     * @param \CharlotteDunois\Yasmin\Models\GuildMember|\CharlotteDunois\Yasmin\Models\User|string|int  $guildmember  string/int = user ID
     * @return \CharlotteDunois\Yasmin\Models\GuildMember
     * @throws \InvalidArgumentException
     */
    function resolve($guildmember) {
        if($guildmember instanceof \CharlotteDunois\Yasmin\Models\GuildMember) {
            return $guildmember;
        }
        
        if($guildmember instanceof \CharlotteDunois\Yasmin\Models\User) {
            $guildmember = $guildmember->id;
        }
        
        if(\is_int($guildmember)) {
            $guildmember = (string) $guildmember;
        }
        
        if(\is_string($guildmember) && parent::has($guildmember)) {
            return parent::get($guildmember);
        }
        
        throw new \InvalidArgumentException('Unable to resolve unknown guild member');
    }
    
    /**
     * {@inheritdoc}
     * @param string  $key
     * @return bool
     */
    function has($key) {
        return parent::has($key);
    }
    
    /**
     * {@inheritdoc}
     * @param string  $key
     * @return \CharlotteDunois\Yasmin\Models\GuildMember|null
     */
    function get($key) {
        return parent::get($key);
    }
    
    /**
     * {@inheritdoc}
     * @param string                                      $key
     * @param \CharlotteDunois\Yasmin\Models\GuildMember  $value
     * @return $this
     */
    function set($key, $value) {
        parent::set($key, $value);
        return $this;
    }
    
    /**
     * {@inheritdoc}
     * @param string  $key
     * @return $this
     */
    function delete($key) {
        parent::delete($key);
        return $this;
    }
    
    /**
     * Factory to create (or retrieve existing) guild members.
     * @param array                                      $data
     * @param \CharlotteDunois\Yasmin\Models\Guild|null  $guild
     * @return \CharlotteDunois\Yasmin\Models\GuildMember
     * @internal
     */
    function factory(array $data, ?\CharlotteDunois\Yasmin\Models\Guild $guild = null) {
        if(parent::has($data['user']['id'])) {
            $member = parent::get($data['user']['id']);
            $member->_patch($data);
            return $member;
        }
        
        $member = new \CharlotteDunois\Yasmin\Models\GuildMember($this->client, ($guild ?? $this->client->guilds->get($this->guildID)), $data);
        $this->set($member->id, $member);
        return $member;
    }
}
