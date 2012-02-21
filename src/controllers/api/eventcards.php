<?php
/**
 * src/controllers/api/eventcards.php
 *
 * PHP version 5
 *
 * @category   PHPFrame_Applications
 * @package    DoC
 * @subpackage ApiControllers
 * @author     Will <will@sliderstudio.co.uk>
 * @copyright  2011 Slider Studio Ltd
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link       http://www.sliderstudio.co.uk
 */

/**
 * Eventcards API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class EventcardsApiController extends PHPFrame_RESTfulController
{
    private $_mapper, $_eventcards_mapper, $_deck_mapper;

    /**
     * Constructor.
     *
     * @param PHPFrame_Application $app Instance of application.
     *
     * @return void
     * @since  1.0
     */
    public function __construct(PHPFrame_Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Get event.
     *
     * @param int $event_id      id of event the cards of which are to be returned.
     *
     * @return array             an array of card objects.
     * @since  1.0
     */
    public function get($event_id, $collection_id=null)
    {
        if (empty($event_id)) {
            $event_id = null;
        }

        if (empty($collection_id)) {
            $collection_id = null;
        }
        
        if (!is_null($event_id)) {
        	$this->_getCardMapper()->context_event($event_id);
            $ret = $this->_getCardMapper()->findByEventId($event_id);
            $this->_getCardMapper()->context_event(false);
            if(!isset($ret)) {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            return $this->handleReturnValue($ret);
        }
    }
    
    public function post($event_id, $card_id=null, $deck_id=null)
    {
        if (empty($event_id)) {
            $event_id = null;
        }
        
        if (empty($card_id)) {
            $card_id = null;
        }
        
        if (empty($deck_id)) {
            $deck_id = null;
        }
        
        //verify valid request
        if(!isset($card_id) && !isset($deck_id)) {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        //verify existence of event and card
        $event = new PHPFrame_Mapper('event',$this->db());
        $event = $event->findOne(intval($event_id));
        $event = isset($event) && $event->id() != 0;
        if(!$event) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        $db = $this->db();
        if(isset($card_id)) {
	        $card = $this->_getCardMapper()->findOne(intval($card_id));
	        $card = isset($card) && $card->id() != 0;
	        if(!$card) {
	            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
	            return;
	        }
	        
	        $params = array(":event_id"=>$event_id, ":card_id"=>$card_id);
        
	        //check for existing/duplicate entry
	        $id_obj = $this->_getMapper()->getIdObject();
	        $id_obj->where('event_id','=',':event_id')
	        ->where('card_id','=',':card_id')
	        ->params(':event_id',$event_id)
	        ->params(':card_id',$card_id);
	        $eventcard = $this->_getMapper()->findOne($id_obj);
	        
	        if(isset($eventcard) && $eventcard->id() > 0)
	        {
	            return $this->handleReturnValue($eventcard);
	        }
	        
	        $eventcard = new Eventcards();
	        $eventcard->event_id($event_id);
	        $eventcard->card_id($card_id);  
	        $this->_getMapper()->insert($eventcard);
	
	        return $this->handleReturnValue($eventcard);
        } else {
            $deck = $this->_getDeckMapper()->findOne(intval($deck_id));
            if(!isset($deck) || !$deck->id()) {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            foreach($deck->cards() as $card) {
            	$params = array(":event_id"=>$event_id, ":card_id"=>$card->id());
        
	            //check for existing/duplicate entry
	            $id_obj = $this->_getMapper()->getIdObject();
	            $id_obj->where('event_id','=',':event_id')
	            ->where('card_id','=',':card_id')
	            ->params(':event_id',$event_id)
	            ->params(':card_id',$card_id);
	            $eventcard = $this->_getMapper()->findOne($id_obj);
	            
	            if(isset($eventcard) && $eventcard->id() > 0)
	            {
	                return $this->handleReturnValue($eventcard);
	            }
	            
	            $eventcard = new Eventcards();
	            $eventcard->event_id($event_id);
	            $eventcard->card_id($card->id());  
	            $this->_getMapper()->insert($eventcard);
            }
        }
    }
    
    public function delete($event_id, $card_id)
    {
        if (empty($event_id)) {
            $event_id = null;
        }
        
        if (empty($card_id)) {
            $card_id = null;
        }
        
        if(!isset($event_id) || !isset($card_id))
        {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;	
        }
        
        //check if entry exists
        $id_obj = $this->_getMapper()->getIdObject();
        $id_obj->where('event_id','=',':event_id')
        ->where('card_id','=',':card_id')
        ->params(':event_id',$event_id)
        ->params(':card_id',$card_id);
        
        $eventcard = $this->_getMapper()->findOne($id_obj);
                
        if(isset($eventcard) && $eventcard->id() > 0)
        {
        	//delete event card mappint
        	$this->_getMapper()->delete($eventcard);
        } else {
            //report error if it entry does not exist
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
    }

    /**
     * Get instance of CardMapper.
     *
     * @return CardMapper
     * @since  1.0
     */
    private function _getCardMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new CardMapper( $this->db());
        }

        return $this->_mapper;
    }
    
    /**
     * Get instance of DeckMapper.
     *
     * @return DeckMapper
     * @since  1.0
     */
    private function _getDeckMapper()
    {
        if (is_null($this->_deck_mapper)) {
            $this->_deck_mapper = new DeckMapper( $this->db());
        }

        return $this->_deck_mapper;
    }
    
    /**
     * Get instance of EventcardsMapper.
     *
     * @return EventcardsMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_eventcards_mapper)) {
            $this->_eventcards_mapper = new PHPFrame_Mapper('eventcards',$this->db());
        }

        return $this->_eventcards_mapper;
    }
}
