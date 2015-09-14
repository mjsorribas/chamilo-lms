<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @license see /license.txt
 * @author autogenerated
 */
class WikiMailcue extends \CourseEntity
{
    /**
     * @return \Entity\Repository\WikiMailcueRepository
     */
     public static function repository(){
        return \Entity\Repository\WikiMailcueRepository::instance();
    }

    /**
     * @return \Entity\WikiMailcue
     */
     public static function create(){
        return new self();
    }

    /**
     * @var integer $c_id
     */
    protected $c_id;

    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var integer $user_id
     */
    protected $user_id;

    /**
     * @var text $type
     */
    protected $type;

    /**
     * @var integer $group_id
     */
    protected $group_id;

    /**
     * @var integer $session_id
     */
    protected $session_id;


    /**
     * Set c_id
     *
     * @param integer $value
     * @return WikiMailcue
     */
    public function set_c_id($value)
    {
        $this->c_id = $value;
        return $this;
    }

    /**
     * Get c_id
     *
     * @return integer 
     */
    public function get_c_id()
    {
        return $this->c_id;
    }

    /**
     * Set id
     *
     * @param integer $value
     * @return WikiMailcue
     */
    public function set_id($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Set user_id
     *
     * @param integer $value
     * @return WikiMailcue
     */
    public function set_user_id($value)
    {
        $this->user_id = $value;
        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function get_user_id()
    {
        return $this->user_id;
    }

    /**
     * Set type
     *
     * @param text $value
     * @return WikiMailcue
     */
    public function set_type($value)
    {
        $this->type = $value;
        return $this;
    }

    /**
     * Get type
     *
     * @return text 
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set group_id
     *
     * @param integer $value
     * @return WikiMailcue
     */
    public function set_group_id($value)
    {
        $this->group_id = $value;
        return $this;
    }

    /**
     * Get group_id
     *
     * @return integer 
     */
    public function get_group_id()
    {
        return $this->group_id;
    }

    /**
     * Set session_id
     *
     * @param integer $value
     * @return WikiMailcue
     */
    public function set_session_id($value)
    {
        $this->session_id = $value;
        return $this;
    }

    /**
     * Get session_id
     *
     * @return integer 
     */
    public function get_session_id()
    {
        return $this->session_id;
    }
}