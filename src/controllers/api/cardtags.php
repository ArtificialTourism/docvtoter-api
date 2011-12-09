<?php
/**
 * src/controllers/api/cardtags.php
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
 * Cardtags API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class CardtagsApiController extends PHPFrame_RESTfulController
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
     * Get card tags.
     *
     * @param int $card_id      id of card for which tags will be returned.
     *
     * @return array        an array containing tags objects.
     * @since  1.0
     */
    public function get($card_id, $tag_id=null)
    {
        if (empty($card_id)) {
            $card_id = null;
        }
        
        if (empty($tag_id)) {
            $tag_id = null;
        }

        //find cardtags for card card_id
        if (isset($card_id)) {
            $id_obj = $this->_getMapper()->getIdObject();
            $id_obj->where('card_id','=',':card_id')
            ->params(':card_id',$card_id);
            if(isset($tag_id)) {
                $id_obj->where('tag_id','=',':tag_id')
                ->params(':tag_id',$tag_id);
            }
            $ret = $this->_getMapper()->find($id_obj);
        }
        
        //cardtags not found for some reason, set error status code
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found cardtags
        return $this->handleReturnValue($ret);
    }
    
    public function post($card_id, $tag_id, $owner=null) 
    {
        if (empty($card_id)) {
            $card_id = null;
        }
        
        if (empty($tag_id)) {
            $tag_id = null;
        }
        
        if (empty($owner)) {
            $owner = null;
        }
        
        //verify existence of card and tag
        $card = new PHPFrame_Mapper('card',$this->db());
        $card = $card->findOne(intval($card_id));
        $card = isset($card) && $card->id() != 0;
        if(!$card) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        $tag = new PHPFrame_Mapper('tags',$this->db());
        $tag = $tag->findOne(intval($tag_id));
        $tag = isset($tag) && $tag->id() != 0;
        if(!$tag) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        
        //fetch any duplicates
        $id_obj = $this->_getMapper()->getIdObject();
        $id_obj->where('card_id','=',':card_id')
        ->where('tag_id','=',':tag_id')
        ->params(':card_id',$card_id)
        ->params(':tag_id',$tag_id);
        if(isset($owner)) {
            $id_obj->where('owner','=',':owner')
            ->params(':owner',$owner);  
        }
        $cardtag = $this->_getMapper()->findOne($id_obj);
        
        //create cardtag
        if(!$cardtag || $cardtag->id() == 0) {
	        $cardtag = new Tagscard();
	        $cardtag->card_id($card_id);
	        $cardtag->tag_id($tag_id);
	        if(isset($owner)) $cardtag->owner($owner);
	        $this->_getMapper()->insert($cardtag);
        }
                
        //return existing/newly created cardtag
        return $this->handleReturnValue($cardtag);
    }
    
    public function delete($card_id, $tag_id)
    {
    	if (empty($card_id)) {
            $card_id = null;
        }
        
        if (empty($tag_id)) {
            $tag_id = null;
        }
        
        //find cardtag
        if(isset($card_id) && isset($tag_id)) {
        	$id_obj = $this->_getMapper()->getIdObject();
        	$id_obj->where('card_id','=',':card_id')
        	->where('tag_id','=',':tag_id')
        	->params(':card_id',$card_id)
        	->params(':tag_id',$tag_id);
            $cardtag = $this->_getMapper()->findOne($id_obj);
            
            //cardtag not found, set error statuscode
            if(!isset($cardtag) || $cardtag->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //delete cardtag
            $this->_getMapper()->delete($cardtag);
        }
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
            $this->_mapper = new PHPFrame_Mapper('tagscard',$this->db());
        }

        return $this->_mapper;
    }
}
