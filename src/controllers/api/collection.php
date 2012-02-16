<?php
/**
 * src/controllers/api/collection.php
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
 * Collection API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class CollectionApiController extends PHPFrame_RESTfulController
{
    private $_mapper, $_event_mapper;

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
     * Get collectoin(s).
     *
     * @param int $id    [Optional] if specified a single category will be returned.
     * @param string $name  [Optional] if specified, collection with that name
     *                      will be returned
     * @return array|object Either a single collcetion object or an array
     *                      containing collection objects.
     * @since  1.0
     */
    public function get($id=null, $name=null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if (empty($name)) {
            $name = null;
        }
        
        if (isset($id)) {
            $ret = $this->_getMapper()->findOne($id);
        } elseif(isset($name)) {
        	$ret = $this->_getMapper()->findByName($name);
        } else {
            $ret = $this->_getMapper()->find();
        }

        return $this->handleReturnValue($ret);
    }

    public function post($name, $params=null, $owner=null)
    {
        $card = new Card();
        
        if (empty($name)) {
            $name = null;
        }
        
        if(isset($name)) {
            $collection->name($name);
            if(!is_null($params)) $collection->params($params);
            if(!is_null($owner)) $collection->owner($owner);
        } else {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        $this->_getMapper()->insert($collection);
        
        return $this->handleReturnValue($collection);
    }
    
    public function put($id, $name=null, $params=null, $owner=null)
    {
        if (empty($id)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        $collection = $this->_getMapper()->findOne(intval($id));
        
        if(!isset($collection) || !$collection->id()) {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
        	return;
        }
        
        if(!is_null($name)) $collection->name($name);
        if(!is_null($owner)) $collection->owner($owner);
        if(!is_null($params)) $collection->params($params);
        
        $this->_getMapper()->insert($collection);
        
        return $this->handleReturnValue($collection);
    }

    /**
     * Get instance of CollectionMapper.
     *
     * @return CollectionMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new CollectionMapper($this->db());
        }

        return $this->_mapper;
    }
}
