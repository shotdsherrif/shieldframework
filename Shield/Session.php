<?php

namespace Shield;

class Session extends Base
{
    /**
     * Path to save the sessions to
     * @var string
     */
    private $_savePathRoot  = '/tmp';

    /**
     * Save path of the saved path
     * @var string
     */
    private $_savePath      = '';

    /**
     * Salt for hashing the session data
     * @var string
     */
    private $_salt          = 't3st1ng#@!';

    /**
     * Init the object, set up the session config handling
     * 
     * @return null
     */
    public function __construct($di)
    {
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );

        parent::__construct($di);
    }

    /**
     * Write to the session
     * 
     * @param integer $id   Session ID
     * @param mixed   $data Data to write to the log
     * 
     * @return null
     */
    public function write($id,$data)
    {
        $path = $this->_savePathRoot.'/'.$id;
        $data = hash('sha256',$this->_salt.'|'.$data);

        file_put_contents($path,$data);
    }

    /**
     * Set the salt for the sesion encryption to use (default is set)
     * 
     * @param string $salt Salt string
     * 
     * @return null
     */
    public function setSalt($salt)
    {
        $this->_salt = $salt;
    }

    /**
     * Read in the session
     * 
     * @param string $id Session ID
     * 
     * @return null
     */
    public function read($id)
    {
        $path = $this->_savePathRoot.'/'.$id;
        return file_get_contents($path);
    }

    /**
     * Close the session
     * 
     * @return boolean Default return (true)
     */
    public function close()
    {
        return true;
    }

    /**
     * Perform garbage collection on the session
     * 
     * @param int $maxlifetime Lifetime in seconds
     * 
     * @return null
     */
    public function gc($maxlifetime)
    {
        // garbage collection
    }

    /**
     * Open the session
     * 
     * @param string $save_path  Path to save the session file locally
     * @param string $session_id Session ID
     * 
     * @return [type]             [description]
     */
    public function open($save_path,$session_id)
    {
        // open session
    }

    /**
     * Destroy the session
     * 
     * @param string $id Session ID
     * 
     * @return null
     */
    public function destroy($id)
    {
        $path = $this->_savePathRoot.'/'.$id;
        if (is_file($path)) {
            unlink($path);
        }
    }

    /**
     * Refresh the session with a new ID
     * 
     * @return null
     */
    public function refresh()
    {
        $sess = $this->_di->get('Input')->getAll('session');
        $id = session_regenerate_id(true);
        session_destroy();
        session_start();
        $_SESSION = $sess;
    }

}

?>