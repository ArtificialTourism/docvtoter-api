<?php
/**
 * src/controllers/api/comment.php
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
 * Comment API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class CommentApiController extends PHPFrame_RESTfulController
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
     * Get comment(s). Either id or owner parameter should be specified or a
     * bad request status code will be returned.
     *
     * @param int $id      [Optional] id of comment to be returned.
     * @param int $owner   [Optional] owner of comment, filter by owner.
     * @param boolean $include_owner [Optional] Default value 0, if set to 1 owner user object
     * will be included in owner_user field
     *
     * @return object|array      a comment object or array of comment objects.
     * @since  1.0
     */
    public function get($id=null, $owner=null, $include_owner=0)
    {
        if (empty($id)) {
            $id = null;
        }

        if (empty($owner)) {
            $owner = null;
        }

        if (!isset($id) && !isset($owner)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }

        if ($include_owner == 1) {
            $this->_getMapper()->include_owner_object(true);
        }

        if (isset($id)) {
            $comment = $this->_getMapper()->findOne(intval($id));

            if(!isset($comment) || $comment->id() == 0)
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
        } elseif (isset($owner)) {
            $comment = $this->_getMapper()->findByOwner($owner);
        }

        $this->_getMapper()->include_owner_object(false);


        
        //return found comment
        return $this->handleReturnValue($comment);
    }
    
    public function post($card_id, $message, $owner=null) 
    {
        if (empty($card_id)) {
            $card_id = null;
        }
        
        if (empty($message)) {
            $message = null;
        }
        
        if (empty($owner)) {
            $owner = null;
        }
        
        if(isset($card_id) && isset($message)) {
        	$comment = new Comment();
        	$comment->card_id($card_id);
        	$comment->message($message);
        	if(isset($owner)) $comment->owner($owner);
        	$this->_getMapper()->insert($comment);
        }
        
        if(!isset($comment)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }        
                
        return $this->handleReturnValue($comment);
    }
    
    public function put($id, $message=null)
    {
        if (empty($id)) {
            $id = null;
        }
    	
        if (empty($message)) {
            $message = null;
        }
        
        //find comment
        if(isset($id)) {
            $comment = $this->_getMapper()->findOne(intval($id));
            
            //comment not found, set error statuscode
            if(!isset($comment) || $comment->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //update comment
            if(isset($message)) $comment->message($message);
            $this->_getMapper()->insert($comment);
        }

        if(!isset($comment))
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }
        
        return $this->handleReturnValue($comment);
    }

    /**
     * Get instance of CommentMapper.
     *
     * @return CommentMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new CommentMapper($this->db());
        }

        return $this->_mapper;
    }
}
