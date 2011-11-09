<?php
class Comment extends PHPFrame_PersistentObject
{

    public $owner_user;

    public function __construct(array $options=null)
    {
    	$this->addField(
            "card_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "message",
            null,
            false,
            new PHPFrame_StringFilter()
        );
        
        parent::__construct($options);
    }

    public function ownerUser(User $owner)
    {
        if (!is_null($owner)) {
            $this->owner_user = $owner;
        }

        return $this->owner_user;
    }
}
