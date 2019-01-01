<?php
/**
 * Yasmin
 * Copyright 2017-2019 Charlotte Dunois, All Rights Reserved
 *
 * Website: https://charuru.moe
 * License: https://github.com/CharlotteDunois/Yasmin/blob/master/LICENSE
*/

namespace CharlotteDunois\Yasmin\Models;

/**
 * Presence Storage, which utilizes Collection.
 */
class PresenceStorage extends Storage implements \CharlotteDunois\Yasmin\Interfaces\PresenceStorageInterface {
    /**
     * Whether the presence cache is enabled.
     * @var bool
     */
    protected $enabled;
    
    /**
     * @internal
     */
    function __construct(\CharlotteDunois\Yasmin\Client $client, ?array $data = null) {
        parent::__construct($client, $data);
        $this->enabled = (bool) $this->client->getOption('presenceCache', true);
    }
    
    /**
     * Resolves given data to a presence.
     * @param \CharlotteDunois\Yasmin\Models\Presence|\CharlotteDunois\Yasmin\Models\User|int|string  $presence  int/string = user ID
     * @return \CharlotteDunois\Yasmin\Models\Presence
     * @throws \InvalidArgumentException
     */
    function resolve($presence) {
        if($presence instanceof \CharlotteDunois\Yasmin\Models\Presence) {
            return $presence;
        }
        
        if($presence instanceof \CharlotteDunois\Yasmin\Models\User) {
            $presence = $presence->id;
        }
        
        if(parent::has($presence)) {
            return parent::get($presence);
        }
        
        throw new \InvalidArgumentException('Unable to resolve unknown presence');
    }
    
    /**
     * {@inheritdoc}
     * @param int  $key
     * @return bool
     */
    function has($key) {
        return parent::has($key);
    }
    
    /**
     * {@inheritdoc}
     * @param int  $key
     * @return \CharlotteDunois\Yasmin\Models\Presence|null
     */
    function get($key) {
        return parent::get($key);
    }
    
    /**
     * {@inheritdoc}
     * @param int                                      $key
     * @param \CharlotteDunois\Yasmin\Models\Presence  $value
     * @return $this
     */
    function set($key, $value) {
        if(!$this->enabled) {
            return $this;
        }
        
        parent::set($key, $value);
        if($this !== $this->client->presences) {
            $this->client->presences->set($key, $value);
        }
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     * @param int  $key
     * @return $this
     */
    function delete($key) {
        if(!$this->enabled) {
            return $this;
        }
        
        parent::delete($key);
        if($this !== $this->client->presences) {
            $this->client->presences->delete($key);
        }
        
        return $this;
    }
    
    /**
     * Factory to create presences.
     * @param array  $data
     * @return \CharlotteDunois\Yasmin\Models\Presence
     * @internal
     */
    function factory(array $data) {
        $presence = new \CharlotteDunois\Yasmin\Models\Presence($this->client, $data);
        $this->set($presence->userID, $presence);
        return $presence;
    }
}
