<?php
/**
 * src/controllers/api/category.php
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
class CategoryApiController extends PHPFrame_RESTfulController
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
     * Get categories.
     *
     * @param int $id      [optional] id of category to be returned.
     *
     * @return array        an array containing category objects.
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

        //categories not found for some reason, set error status code
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found categories
        return $this->handleReturnValue($ret);
    }
    
    public function post($name, $collection=null) 
    {
        if (empty($name)) {
            $name = null;
        }
        
        if (empty($tag_id)) {
            $tag_id = null;
        }
        
        //check duplicate name & collection
        $id_obj = $this->_getMapper()->getIdObj();
        $id_obj->where('name','=',':name')
        ->params(':name',$name);
        if(!isset($collection)) {
        	$id_obj->where('collection','IS','NULL');
        } else {
        	$id_obj->where('collection','=',':collection')
        	->params(':collection',$collection);
        }
        $category = $this->_getMapper()->findOne($id_obj);
        
        //if not duplicate, create new category
        if(!isset($category) || $category->id() == 0)
        {
        	$category = new Category();
        	$category->name($name);
        	if(isset($collection)) $category->collection($collection);
        	$this->_getMapper()->insert($category);
        }
        
        return $this->handleReturnValue($category);
    }
    
    public function put($id, $name=null, $collection=null)
    {
        if (empty($id)) {
            $id = null;
        }
    	
    	if (empty($name)) {
            $name = null;
        }
        
        if (empty($collection)) {
            $collection = null;
        }
        
        //find category
        if(isset($id)) {
            $category = $this->_getMapper()->findOne($id);
            
            //category not found, set error statuscode
            if(!isset($category) || $category->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //update category
            if(isset($name)) $category->name($name);
            if(isset($collection)) $category->collection($collection);
            $this->_getMapper()->insert($category);
        }

        if(!isset($category))
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }
        
        return $this->handleReturnValue($category);
    }

    /**
     * Get instance of CategoryMapper.
     *
     * @return CategoryMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('category', $this->db());
        }

        return $this->_mapper;
    }
}
