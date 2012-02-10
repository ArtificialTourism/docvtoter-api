<?php
/**
 * src/controllers/api/deckcard.php
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
 * Deckcard API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class DeckcardApiController extends PHPFrame_RESTfulController
{
    private $_mapper;

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
     * Get deck cards.
     *
     * @param int $deck_id      id for deck of which cards will be returned.
     *
     * @return array            an array containing card objects.
     * @since  1.0
     */
    public function get($deck_id)
    {
        if (empty($deck_id)) {
            $deck_id = null;
        }

        if (!is_null($deck_id)) {
            $ret = $this->_getCardMapper()->findByDeckId($deck_id);
            
            if(!isset($ret)) {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            return $this->handleReturnValue($ret);
        }
    }
    
    public function post($deck_id, $card_id)
    {
        if (empty($deck_id)) {
            $deck_id = null;
        }
        
        if (empty($card_id)) {
            $card_id = null;
        }
        
        //verify existence of deck and card
        $card = $this->_getCardMapper()->findOne(intval($card_id));
        $card = isset($card) && $card->id() != 0;
        if(!$card) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        $deck = new PHPFrame_Mapper('deck',$this->db());
        $deck = $deck->findOne(intval($deck_id));
        $deck = isset($deck) && $deck->id() != 0;
        if(!$deck) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        $db = $this->db();
        $params = array(":deck_id"=>$deck_id, ":card_id"=>$card_id);
        
        //check for existing/duplicate entry
        $sql = "SELECT * FROM deckcards WHERE deck_id = :deck_id AND card_id = :card_id";
        $deckcard = $db->fetchAssoc($sql, $params); 

        if(!isset($deckcard['card_id']) || !$deckcard['deck_id'])
        {
            $insert_sql = "INSERT INTO deckcards (deck_id, card_id) values(:deck_id, :card_id)";
            $db->query($insert_sql, $params);
        }

        $deckcard = $db->fetchAssoc($sql, $params);
        if(isset($deckcard['card_id']) && $deckcard['deck_id']) {
            return $this->handleReturnValue($deckcard);
        } else {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
    }
    
    public function delete($deck_id, $card_id)
    {
        if (empty($deck_id)) {
            $deck_id = null;
        }
        
        if (empty($card_id)) {
            $card_id = null;
        }
        
    	$db = $this->db();
        $params = array(":deck_id"=>$deck_id, ":card_id"=>$card_id);
        
        //check if entry exists
        $sql = "SELECT * FROM deckcards WHERE deck_id = :deck_id AND card_id = :card_id";
        $deckcard = $db->fetchAssoc($sql, $params);

        if(isset($deckcard['card_id']) && $deckcard['card_id'])
        {
        	//if it exists, delete
        	$delete_sql = "DELETE FROM deckcards WHERE deck_id = :deck_id AND card_id = :card_id";
        	$db->query($delete_sql,$params);
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
            $db = $this->_mapper = new CardMapper($this->db());
        }

        return $this->_mapper;
    }
}
