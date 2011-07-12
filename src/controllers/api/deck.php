<?php
/**
 * src/controllers/api/deck.php
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
 * Card API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class DeckApiController extends PHPFrame_RESTfulController
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
     * Get card(s).
     *
     * @param int $id       deck to be returned.
     *
     * @return array|object Either a single card object or an array containing
     *                      card objects.
     * @since  1.0
     */
    public function get($id)
    {
        if (empty($id)) {
            $id = null;
        }

        if (!is_null($id)) {
            $ret = $this->_fetchDeck($id);
            
            if(!isset($ret) || !$ret->id()) {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
//var_dump($ret);exit;            
            return $this->handleReturnValue($ret);
        }
    }
    
    public function post($name, $description=null, $id=null)
    {
        if (empty($id)) {
            $id = null;
        }

        if(is_null($id)) {
            $deck = new Deck();
        } else {
        	$deck = $this->_fetchDeck($id);
        }
        
        $deck->name($name);
        if(!is_null($description)) $deck->description($description);
        
        $this->_getDeckMapper()->insert($deck);
        
        return $this->handleReturnValue($deck);
    }
    
    public function put($id, $name=null, $description=null)
    {
        if (!empty($id)) {
            $deck = $this->_fetchDeck($id);
            if(!isset($deck) || !$deck->id()) {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            	return;
            }
            
	        if(!is_null($name)) $deck->name($name);
	        if(!is_null($description)) $deck->description($description);
	        
	        $this->_getDeckMapper()->insert($deck);
	        
	        return $this->handleReturnValue($deck);
        }
    }
    
    /**
     * Fetch a deck by ID.
     *
     * @param int  $id The user id.
     *
     * @return User
     * @since  1.0
     */
    private function _fetchDeck($id)
    {
//        return $this->fetchObj($this->_getDeckMapper(), $id);
          return $this->_getDeckMapper()->findOne(intval($id));
    }

    /**
     * Get instance of DeckMapper.
     *
     * @return CardMapper
     * @since  1.0
     */
    private function _getDeckMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new DeckMapper($this->db());
        }

        return $this->_mapper;
    }
}
