<?php
/**
 * src/controllers/api/organisation.php
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
 * Organisation API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class OrganisationApiController extends PHPFrame_RESTfulController
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
     * Get organisations.
     *
     * @param int $id           [optional] id of organisation to be returned.
     *
     * @return object|arrray    an organisation object or array of them if no id given.
     * @since  1.0
     */
    public function get($id=null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if(!is_null($id)) {
            $ret = $this->_getMapper()->findOne(intval($id));
        } else {
        	$ret = $this->_getMapper()->find();
        }
        
        //organisation(s) not found for some reason, set error status code
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found organisation(s)
        return $this->handleReturnValue($ret);
    }
    
    public function post($name, $location_id=null, $parent_id=null) 
    {
        if (empty($name)) {
            $name = null;
        }
        
        if (empty($location_id)) {
            $location_id = null;
        }
        
        if (empty($parent_id)) {
        	$parent_id = null;
        }
        
        if(isset($name)) {
        	$organisation = new Organisation();
        	$organisation->name($name);
        	if(isset($location_id)) $organisation->location_id($location_id);
        	if(isset($parent_id)) $organisation->parent_id($parent_id);
        	
        	$this->_getMapper()->insert($organisation);
        }
        
        if(!isset($organisation)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }        
                
        return $this->handleReturnValue($organisation);
    }
    
    public function put($id, $name=null, $location_id=null, $parent_id=null)
    {
        if (empty($id)) {
            $id = null;
        }
    	
        //find organisation
        if(isset($id)) {
            $organisation = $this->_getMapper()->findOne($id);
            
            //organisation not found, set error statuscode
            if(!isset($organisation) || $organisation->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //update organisation
            if(isset($name)) $organisation->name($name);
            if(isset($location_id)) $organisation->location_id($location_id);
            if(isset($parent_id)) $organisation->parent_id($parent_id);
            
            $this->_getMapper()->insert($organisation);
        }

        if(!isset($organisation))
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }
        
        return $this->handleReturnValue($organisation);
    }

    /**
     * Get instance of OrganisationMapper.
     *
     * @return OrganisationMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('organisation', $this->db());
        }

        return $this->_mapper;
    }
}
