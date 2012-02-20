<?php
class Deck extends PHPFrame_PersistentObject implements PHPFrame_RESTfulObject
{
	private $_cards;
	public $card_count;
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "name",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>100))
        );
        $this->addField(
            "description",
            null,
            true,
            new PHPFrame_StringFilter()
        );
        
        $this->_cards = new SplObjectStorage();
        
        parent::__construct($options);
    }
    
    public function addCard($card)
    {
        $this->_cards->attach($card);
    }
    
    public function cards()
    {
        $cards = iterator_to_array($this->_cards);
        if(is_array($cards)) return $cards;
    }
    
    public function getRESTfulRepresentation()
    {
    	$restful = iterator_to_array($this);
//    	$cards = iterator_to_array($this->_cards);
    	$card_array = array();
    	foreach($this->_cards as $card) {
    		$card_array[] = iterator_to_array($card);
    	}
    	$restful['cards'] = $card_array;
//var_dump($restful);exit;
    	return $restful;
    }
    
    public function cardCount($count=null)
    {
        if (!is_null($count)) {
            $this->card_count = $count;
        }

        return $this->card_count;
    }
}
