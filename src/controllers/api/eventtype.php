<?php
/**
 * src/controllers/api/eventtype.php
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
 * Eventtype API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class EventtypeApiController extends PHPFrame_RESTfulController
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
     * Get event eventtypes.
     *
     * @param int $event_id     id of event for which eventtypes will be returned.
     *
     * @return array            an array containing eventtype objects.
     * @since  1.0
     */
    public function get($event_id)
    {
        if (empty($event_id)) {
            $event_id = null;
        }

        //find eventtypes for card card_id
        if (!is_null($event_id)) {
            $id_obj = $this->_getMapper()->getIdObject();
            $id_obj->where('event_id','=',':event_id')
            ->params(':event_id',$event_id);
            $ret = $this->_getMapper()->find($id_obj);
        }
        
        //cardtags not found for some reason, set error status code
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found eventtypes
        return $this->handleReturnValue($ret);
    }
    
    public function post($event_id, $eventtype_id) 
    {
        if (empty($event_id)) {
            $event_id = null;
        }
        
        if (empty($eventtype_id)) {
            $eventtype_id = null;
        }
        
        //verify existence of eventy and eventtype
        $event = new PHPFrame_Mapper('event',$this->db());
        $event = $event->findOne(intval($event_id));
        $event = isset($event) && $event->id() != 0;
        if(!$event) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        $type = new PHPFrame_Mapper('eventtype',$this->db());
        $type = $type->findOne(intval($eventtype_id));
        $type = isset($type) && $type->id() != 0;
        if(!$type) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        
        //create eventeventtype
        $eventtype = new Eventeventtype();
        $eventtype->event_id($event_id);
        $eventtype->type_id($eventtype_id);
        $this->_getMapper()->insert($eventtype);
                
        //return newly created eventeventtype mapping
        return $this->handleReturnValue($eventtype);
    }
    
    public function delete($event_id, $eventtype_id)
    {
    	if (empty($event_id)) {
            $event_id = null;
        }
        
        if (empty($eventtype_id)) {
            $eventtype_id = null;
        }
        
        //find eventeventtype
        if(isset($event_id) && isset($eventtype_id)) {
        	$id_obj = $this->_getMapper()->getIdObject();
        	$id_obj->where('event_id','=',':event_id')
        	->where('type_id','=',':type_id')
        	->params(':event_id',$event_id)
        	->params(':type_id',$eventtype_id);
            $eventtype = $this->_getMapper()->findOne($id_obj);
            
            //eventtype not found, set error statuscode
            if(!isset($eventtype) || $eventtype->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //delete eventtype
            $this->_getMapper()->delete($eventtype);
        }
    }

    /**
     * Get instance of EventeventtypeMapper.
     *
     * @return EventeventtypeMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('eventeventtype', $this->db());
        }

        return $this->_mapper;
    }
}
