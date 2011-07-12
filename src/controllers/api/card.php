<?php
/**
 * src/controllers/api/card.php
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
class CardApiController extends PHPFrame_RESTfulController
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
     * Get user(s).
     *
     * @param int $id    [Optional] if specified a single card will be returned.
     * @param int $limit [Optional] Default value is 10.
     * @param int $page  [Optional] Default value is 1.
     *
     * @return array|object Either a single card object or an array containing
     *                      card objects.
     * @since  1.0
     */
    public function get($id=null, $limit=10, $page=1)
    {
        if (empty($id)) {
            $id = null;
        }

        if (empty($limit)) {
            $limit = 10;
        }

        if (empty($page)) {
            $page = 1;
        }

        if (!is_null($id)) {
            $ret = $this->_fetchCard($id);
        } else {
            $id_obj = $this->_getMapper()->getIdObject();
            $id_obj->limit($limit, ($page-1)*$limit);
            $ret = $this->_getMapper()->find($id_obj);
        }

        return $this->handleReturnValue($ret);
    }

    public function post($name, $category_id, $type, $safe_name=null, $topic_id=null,
        $question=null, $factoid=null, $description=null, $image=null, $card_front=null,
        $card_back=null, $origin_event_id=null, $uri=null, $params=null
    )
    {
        $card = new Card();
        
        if (empty($name)) {
            $name = null;
        }
        
        if (empty($category_id)) {
            $category_id = null;
        }
        
        if (empty($type)) {
            $type = null;
        }
        
        if(isset($name) && isset($safe_name) && isset($category_id) && isset($type)) {
            $card->name($name);
            $card->category_id($category_id);
            $card->type($type);
            if(!is_null($safe_name)) $card->safe_name($safe_name);
            if(!is_null($topic_id)) $card->topic_id($topic_id);
            if(!is_null($question)) $card->question($question);
            if(!is_null($factoid)) $card->factoid($factoid);
            if(!is_null($description)) $card->description($description);
            if(!is_null($image)) $card->image($image);
            if(!is_null($card_front)) $card->card_front($card_front);
            if(!is_null($card_back)) $card->card_back($card_back);
            if(!is_null($origin_event_id)) $card->origin_event_id($origin_event_id);
            if(!is_null($uri)) $card->uri($uri);
            if(!is_null($params)) $card->params($params);
        } else {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        $this->_getMapper()->insert($card);
        
        return $this->handleReturnValue($card);
    }
    
    public function put($id, $name=null, $safe_name=null, $category_id=null,
        $type=null, $topic_id=null, $question=null, $factoid=null, $description=null,
        $image=null, $card_front=null, $card_back=null, $origin_event_id=null,
        $uri=null, $params=null
    )
    {
        if (empty($id)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        $card = $this->_getMapper()->findOne(intval($id));
        
        if(!isset($card) || !$card->id()) {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
        	return;
        }
        
        if(!is_null($name)) $card->name($name);
        if(!is_null($safe_name)) $card->safe_name($safe_name);
        if(!is_null($category_id)) $card->category_id($category_id);
        if(!is_null($type)) $card->type($type);
        if(!is_null($topic_id)) $card->topic_id($topic_id);
        if(!is_null($question)) $card->question($question);
        if(!is_null($factoid)) $card->factoid($factoid);
        if(!is_null($description)) $card->description($description);
        if(!is_null($image)) $card->image($image);
        if(!is_null($card_front)) $card->card_front($card_front);
        if(!is_null($card_back)) $card->card_back($card_back);
        if(!is_null($origin_event_id)) $card->origin_event_id($origin_event_id);
        if(!is_null($uri)) $card->uri($uri);
        if(!is_null($params)) $card->params($params);
        
        $this->_getMapper()->insert($card);
        
        return $this->handleReturnValue($card);
    }

    /**
     * Delete card.
     *
     * @param int $id The card id.
     *
     * @return void
     * @since  1.0
     */
    public function delete($id)
    {
        if (empty($id)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        $card = $this->_getMapper()->findOne(intval($id));
        
        if(!isset($card) || !$card->id()) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        
        $this->_getMapper()->delete($card);
    }
    
    /**
     * Fetch a card by ID and check read access.
     *
     * @param int  $id The user id.
     *
     * @return User
     * @since  1.0
     */
    private function _fetchCard($id)
    {
        return $this->fetchObj($this->_getMapper(), $id);
    }

    /**
     * Get instance of CardMapper.
     *
     * @return CardMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('card', $this->db());
        }

        return $this->_mapper;
    }
}
