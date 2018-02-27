<?php 

namespace hokphp\core\components;

use hokphp\core\components\Application;

/**
* Base model class
*/
abstract class Model
{
	/**
	 * Validation errors
	 * @var array
	 */
	public $errors = [];

	/**
	 * Returns the table name for this model
	 * @return string
	 */
	abstract public function tableName();

	/**
	 * Returns this model's fields
	 * @return array
	 */
	abstract public function getFields();

	/**
	 * Validation rules per field
	 * @return array
	 */
	abstract public function rules();

	/**
	 * Connects to the database
	 * @return ODBC result
	 */
	public static function connectDatabase()
	{
		$dsn = Application::$app->params['dsn'];
		$user = Application::$app->params['username'];
		$password = Application::$app->params['password'];
		$connection = odbc_connect($dsn, $user, $password);
		if (!$connection) {
		    throw new \Exception('Cant connect to database');
		}
		return $connection;
	}

	/**
	 * Executes the sql query using odbc_exec()
	 * @param  string  $sqlQuery  The query to execute
	 * @param  boolean $insert    Wether a insert query is being executed
	 * @return ODBC query result 
	 */
	public function query($sqlQuery, $insert = FALSE)
	{
		$rs = odbc_exec(Application::$dbConnection, $sqlQuery);
		if ($insert) {
			return $rs ? TRUE : FALSE;
		}
	    $row = odbc_fetch_row($rs);
	    if (!$row) {
	        return NULL;
	    } else {
			return $rs;
	    }
	}

	/**
	 * Returns the value of a field in a row using odbc_result()
	 * @param  ODBC Query Result  $result            The query result/resource identifier
	 * @param  integer 			  $fieldNameOrIndex  
	 * @return mixed
	 */
	public function getValue($result, $fieldNameOrIndex)
	{
		return odbc_result($result, $fieldNameOrIndex);
	}

	/**
	 * Finds a model using the id
	 * @param  integer $id  The unique identifier IncidentNr
	 * @return model|NULL	The model or NULL if not found
	 */
	public static function findById($id)
	{
		$model = new static;
	    $sql = $model->addWhere($model->createSelectQuery(), ['Id'=>$id]);
	    $result = $model->query($sql);
	    if ($result) {
	    	$model->populateModel($result);
	    	return $model;
	    } else {
	    	return NULL;
	    }
	}

	/**
	 * Creates the raw SQL query
	 * @param  array $select 	The fields to select. If empty, all fields are selected.
	 * @return string 			The raw SQL query
	 */
	public function createSelectQuery($selectFields = [])
	{
		return $this->addSelect($selectFields)." FROM ".$this->tableName();
	}

	/**
	 * Creates the select clause string using all fields
	 * @return string  The select clause string 
	 */
	public function addSelect()
	{
		$i = 0;
		$string = "SELECT ";
		foreach ($this->getFields() as $fieldArray) {
			$string .= ($i > 0) ? ", " : "";
			if ($fieldArray['type'] == 'int') {
				$string .= "[".ucfirst($fieldArray['field'])."]";
			} elseif($fieldArray['type'] == 'datetime') {
				$string .= "[".ucfirst($fieldArray['field'])."]";
			} elseif($fieldArray['type'] == 'date') {
				$string .= "[".ucfirst($fieldArray['field'])."]";
			} else {
				$string .= "CAST([".ucfirst($fieldArray['field'])."] AS TEXT) as [".$fieldArray['field']."]";
			}
			$i++;
		}
		return $string;
	}

	/**
	 * Adds a where clause to the given query
	 * @param string $sql       The raw query
	 * @param array  $condition The where condition
	 * @return string 			The raw query
	 */
	public function addWhere($sql, $condition = [])
	{
		if (is_array($condition) && !empty($condition)) {
			$i = 0;
			foreach ($condition as $key => $value) {
				$sql .= ($i < 1) ? " WHERE " : " AND ";
				$sql .= is_string($value) ? "$key = '".$value."'" : "$key = ".$value."";
				$i++;
			}
		}
		return $sql;
	}

	/**
	 * Populates a this model with values from the database in $queryResult
	 * @return void
	 */
	public function populateModel($queryResult)
	{
		foreach ($this->getFields() as $i => $array) {
			// field index = array index + 1 (array index starts at 0)
			$this->$array['field'] = $this->getValue($queryResult, $i + 1);
		}
	}

	/**
	 * Populates the current model with data using mass assignment
	 * @param  array $newData   The array containing the post data
	 * @param  array $scenario  The key value pairs of the fields to be processed
	 * @return boolean 			Wether the mass assignment was successful
	 */
	public function assign($newData, $scenario = [])
	{
		if (!empty($newData)) {
			foreach ($newData as $key => $data) {
				if (array_key_exists($key, $scenario)) {
					$this->$scenario[$key] = $data;
				}
			}
		}
	}

	/**
	 * Saves the given fields to the database
	 * @param  array   $fields     The fields to save to the db
	 * @param  boolean $validate   Wether to validate before saving
	 * @return boolean 	           Wether the fields were succesfully saved
	 */
	public function save($fields, $validate = TRUE)
	{
		if ($validate) {
			if (!$this->validate($fields)) {
				return FALSE;
			}
		}
		$updateString = '';
		foreach ($fields as $field) {
			$updateString .= "[".ucfirst($field)."] = '".$this->$field."', ";
		}
		$updateString = rtrim($updateString, ', ');
	    $sql = "UPDATE ".$this->tableName()." SET ".$updateString." WHERE Id = ".$this->id;
		return $this->query($sql, TRUE);
	}

	/**
	 * Returns the first error in the errors array of this model
	 * @return string
	 */
	public function getFirstError()
	{
		if (!empty($this->errors)) {
			foreach ($this->errors as $field => $errorArray) {
				return $errorArray[0];
			}
		}
		return NULL;
	}
}