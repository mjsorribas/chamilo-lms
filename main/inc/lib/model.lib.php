<?php
/* For licensing terms, see /license.txt */

/**
 * Class Model
 * This class provides basic methods to implement a CRUD for a new table in the
 * database see examples in: career.lib.php and promotion.lib.php
 * Include/require it in your code to use its features.
 *
 * @package chamilo.library
 */
class Model
{
    public $table;
    public $columns;
    public $required;
    public $is_course_model = false;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Useful finder - experimental akelos like only use in notification.lib.php send function
     */
    public function find($type, $options = null)
    {
        switch ($type) {
            case 'all':
                return self::get_all($options);
                break;
            default:
                if (is_numeric($type)) {
                    return self::get($type);
                }
                break;
        }
    }

    /**
     * Deletes an item
     * @param int $id
     *
     * @return bool
     */
    public function delete($id)
    {
        if (empty($id) or $id != strval(intval($id))) {
            return false;
        }
        $params = array('id = ?' => $id);
        if ($this->is_course_model) {
            $course_id = api_get_course_int_id();
            $params = array('id = ? AND c_id = ?' => array($id, $course_id));
        }
        // Database table definition
        $result = Database::delete($this->table, $params);
        if ($result != 1) {
            
            return false;
        }

        return true;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    private function clean_parameters($params)
    {
        $clean_params = array();
        if (!empty($params)) {
            foreach ($params as $key=>$value) {
                if (in_array($key, $this->columns)) {
                    $clean_params[$key] = $value;
                }
            }
        }

        return $clean_params;
    }

    /**
     * Displays the title + grid
     */
    public function display()
    {
    }

    /**
     * Gets an element
     * @param int $id
     *
     * @return array|mixed
     */
    public function get($id)
    {
        if (empty($id)) {
            return array();
        }
        $params = array('id = ?' => intval($id));
        if ($this->is_course_model) {
            $course_id = api_get_course_int_id();
            $params = array('id = ? AND c_id = ?' => array($id, $course_id));
        }
        $result = Database::select(
            '*',
            $this->table,
            array('where' => $params),
            'first'
        );

        return $result;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function get_all($options = null)
    {
        return Database::select('*', $this->table, $options);
    }

    /**
     * @param array  $options
     *
     * @return array
     */
    public function getDataToExport($options = array())
    {
        return Database::select('name, description', $this->table, $options);
    }

    /**
     * Get the count of elements
     */
    public function get_count()
    {
        $row = Database::select(
            'count(*) as count',
            $this->table,
            array('where' => array('parent_id = ?' => '0')),
            'first'
        );

        return $row['count'];
    }

    /**
     * a little bit of javascript to display
     */
    public function javascript()
    {
    }

    /**
     * Saves an element into the DB
     * @param array $params
     * @param bool  $show_query Whether to show the query in logs or not (passed to Database::insert())
     * @return bool
     *
     */
    public function save($params, $show_query = false)
    {
        $params = $this->clean_parameters($params);

        if ($this->is_course_model) {
            if (!isset($params['c_id']) || empty($params['c_id'])) {
                $params['c_id'] = api_get_course_int_id();
            }
        }

        if (!empty($this->required)) {
            $require_ok = true;
            $key_params = array_keys($params);
            foreach ($this->required as $field) {
                if (!in_array($field, $key_params)) {
                    $require_ok = false;
                }
            }
            if (!$require_ok) {
                return false;
            }
        }

        if (in_array('created_at', $this->columns)) {
            $params['created_at'] = api_get_utc_datetime();
        }

        if (in_array('updated_at', $this->columns)) {
            $params['updated_at'] = api_get_utc_datetime();
        }

        if (!empty($params)) {
            $id = Database::insert($this->table, $params, $show_query);
            if (is_numeric($id)) {
                return $id;
            }
        }

        return false;
    }

    /**
     * Updates the obj in the database. The $params['id'] must exist in order to update a record
     * @param array $params
     *
     * @return bool
     *
     */
    public function update($params)
    {
        $params = $this->clean_parameters($params);

        if ($this->is_course_model) {
            if (!isset($params['c_id']) || empty($params['c_id'])) {
                $params['c_id'] = api_get_course_int_id();
            }
        }

        //If the class has the updated_at field we update the date
        if (in_array('updated_at', $this->columns)) {
            $params['updated_at'] = api_get_utc_datetime();
        }
        //If the class has the created_at field then we remove it
        if (in_array('created_at', $this->columns)) {
            unset($params['created_at']);
        }

        if (!empty($params) && !empty($params['id'])) {
            $id = intval($params['id']);
            unset($params['id']); //To not overwrite the id
            if (is_numeric($id)) {
                $result = Database::update(
                    $this->table,
                    $params,
                    array('id = ?' => $id)
                );
                if ($result) {
                    return true;
                }
            }
        }

        return false;
    }
}
