<?php
/**
 * src/controllers/api/vote.php
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
 * Vote API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class VoteApiController extends PHPFrame_RESTfulController
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
     * Get vote count.
     *
     * @param int $eventcards_id     id of eventcard for which vote count is to be returned.
     *
     * @return string   vote count
     * @since  1.0
     */
    public function get($eventcards_id)
    {
        if (empty($eventcards_id)) {
            $eventcards_id = null;
        }
        if(!is_null($eventcards_id)) {
            $id_obj = $this->_getMapper()->getIdObject();
            $table = $id_obj->getTableName();
	        $id_obj->select("COUNT(eventcards_id)")
	           ->from($table)
	           ->where('eventcards_id','=',':eventcards_id')
	           ->groupby('eventcards_id');
	        $params = array(':eventcards_id'=>$eventcards_id);
	        $sql = $id_obj->getSQL();
	        $count = $this->db()->fetchAssoc($id_obj, $params);
	        if(isset($count['COUNT(eventcards_id)'])) {
	            $count = $count['COUNT(eventcards_id)'];
	        } else {
	        	$count = '0';
	        } 
        }

        //return count
        return $this->handleReturnValue($count);
    }
    
    public function post($eventcards_id, $ip_address=null, $owner=null)
    {
        if (empty($eventcards_id)) {
            $eventcards_id = null;
        }
        
        if(!is_null($eventcards_id))
        {
        	$vote = new Vote();
        	$vote->eventcards_id($eventcards_id);
        	if(isset($ip_address)) $vote->ip_address($ip_address);
        	if(isset($owner)) $vote->owner($owner);
        	$this->_getMapper()->insert($vote);
        }
        
        return $this->handleReturnValue($vote);
    }
    
    public function delete($id)
    {
        if (empty($id)) {
            $id = null;
        }
        
        //find vote
        if(isset($id)) {
            $vote = $this->_getMapper()->findOne(intval($id));
            
            //vote not found, set error statuscode
            if(!isset($vote) || $vote->id() == 0)
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //delete vote
            $this->_getMapper()->delete($vote);
        }
    }
    
    /**
     * Get instance of VoteMapper.
     *
     * @return VoteMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('vote', $this->db());
        }

        return $this->_mapper;
    }
}
