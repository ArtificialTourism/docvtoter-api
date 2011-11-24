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
     * @param int $owner        [optional] owner of organisation, filter by owner.
     *
     * @return object|arrray    an organisation object or array of them if no id given.
     * @since  1.0
     */
    public function get($id=null, $owner=null)
    {
        if (empty($id)) {
            $id = null;
        }

        if (empty($owner)) {
            $owner = null;
        }
        
        if(!is_null($id)) {
            $ret = $this->_getMapper()->findOne(intval($id));
        } elseif (!is_null($owner)) {
            $ret = $this->_getMapper()->findByOwner($owner);
        } else {
        	$ret = $this->_getMapper()->find();
        }

        $this->logger()->write(print_r($ret, true));
        
        //organisation(s) not found for some reason, set error status code
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found organisation(s)
        return $this->handleReturnValue($ret);
    }
    
    public function post($name, $location_id=null, $parent_id=null, $sector=null, $owner=null)
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

        if (empty($sector)) {
            $sector = null;
        }
        
        if (empty($owner)) {
            $owner = null;
        }
        
        if(isset($name)) {
        	$organisation = new Organisation();
        	$organisation->name($name);
        	if(isset($location_id)) $organisation->location_id($location_id);
        	if(isset($parent_id)) $organisation->parent_id($parent_id);
            if(isset($sector)) $organisation->sector($sector);
        	if(isset($owner)) $organisation->owner($owner);
        	
        	$this->_getMapper()->insert($organisation);
        }
        
        if(!isset($organisation)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }        
                
        return $this->handleReturnValue($organisation);
    }
    
    public function put($id, $name=null, $location_id=null, $parent_id=null, $sector=null, $owner=null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if(empty($owner)) {
        	$owner = null;
        }
    	
        //find organisation
        if(isset($id)) {
            $organisation = $this->_getMapper()->findOne(intval($id));
            
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
            if(isset($sector)) $organisation->sector($sector);
            if(isset($owner)) $organisation->owner($owner);
            
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
            $this->_mapper = new OrganisationMapper($this->db());
        }

        return $this->_mapper;
    }
}
