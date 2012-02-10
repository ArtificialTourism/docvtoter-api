<?php
class Card extends PHPFrame_PolymorphicPersistentObject
{

    public $owner_user, $category_tag_id;

    public function __construct(array $options=null)
    {
    	$this->addField(
            "name",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
    	$this->addField(
            "safe_name",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        $this->addField(
            "category_tag_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "topic_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "question",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "factoid",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>1023))
        );
        $this->addField(
            "description",
            null,
            true,
            new PHPFrame_StringFilter()
        );
        $this->addField(
            "image",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "card_front",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "card_back",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "origin_event_id",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "uri",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>255))
        );
        $this->addField(
            "status",
            "active",
            false,
            new PHPFrame_EnumFilter(array(
                 "enums" => array(
                     "active",
                     "deleted"
                  )
            ))            
        );
        parent::__construct($options);
    }

    public function ownerUser(User $owner=null)
    {
        if (!is_null($owner)) {
            $this->owner_user = $owner;
        }

        return $this->owner_user;
    }
    
    public function eventCategoryId($event_category_id=null)
    {
        if (!is_null($event_category_id)) {
            $this->category_tag_id = $event_category_id;
        }

        return $this->category_tag_id;
    }
}
