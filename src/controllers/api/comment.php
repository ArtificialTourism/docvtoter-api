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
     * Get comment.
     *
     * @param int $id      id of comment to be returned.
     *
     * @return object      a comment object.
     * @since  1.0
     */
    public function get($id)
    {
        if (empty($id)) {
            $id = null;
        }
        
        $comment = $this->_getMapper()->findOne($id);

        if(!isset($comment) || $comment->id() == 0)
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        
        //return found comment
        return $this->handleReturnValue($comment);
    }
    
    public function post($card_id, $message) 
    {
        if (empty($card_id)) {
            $card_id = null;
        }
        
        if (empty($message)) {
            $message = null;
        }
        
        if(isset($card_id) && isset($message)) {
        	$comment = new Comment();
        	$comment->card_id($card_id);
        	$comment->message($message);
        	
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
            $comment = $this->_getMapper()->findOne($id);
            
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
            $this->_mapper = new PHPFrame_Mapper('comment', $this->db());
        }

        return $this->_mapper;
    }
}
