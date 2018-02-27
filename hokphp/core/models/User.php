<?php 

namespace hokphp\core\models;

use hokphp\core\components\Model as BaseModel;

/**
* User class
*/
class User extends BaseModel
{
	public $id;
	public $username;
	public $password;
	public $lastLogin;

	/**
	 * Validation rules per field
	 * @return array
	 */
	public function rules()
	{
		return [
			'username'=>[
				'type'=>'string',
				'required'=>true,
				'maxlength'=>32
			],
			'password'=>[
				'type'=>'string',
				'required'=>true,
				'maxlength'=>64
			],
			'lastLogin'=>[
				'type'=>'datetime',
				'required'=>false
			],
		];
	}

	/**
	 * Returns the table name for this model
	 * @return string   The table name
	 */
	public function tableName()
	{
		return "[PRD_CustSatisfact_SSC].[dbo].[User]";
	}

	/**
	 * Validates the given fields
	 * @param  array $fields  	The fields to validate
	 * @return boolean			Wether all fields are valid
	 */
	public function validate($fields)
	{
		foreach ($fields as $field) {
			$rules = static::rules();
			if (array_key_exists($field, $rules) && isset($this->$field)) {
				foreach ($rules[$field] as $ruleName => $ruleValue) {
					if ($ruleName == 'required' && $ruleValue === TRUE) {
						if (empty($this->$field)) {
							$this->errors[$field][] = 'Vergeet niet op een duimpje te klikken, dan weten wij hoe tevreden je bent.';
						}
					}
					if ($ruleName == 'range') {
						if (!in_array($this->$field, $ruleValue)) {
							$this->errors[$field][] = 'Onbekende waarde. Klik op een duimpje om je tevredenheid door te geven.';
						}
					}
					if ($ruleName == 'maxlength') {
						if (strlen($this->$field) > $ruleValue) {
							$this->errors[$field][] = 'De toelichting mag niet langer zijn dan 500 tekens.';	
						}
					}
				}
			}
		}
		if (!empty($this->errors)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Returns this model's fields
	 * @return array  The array of fields
	 */
	public function getFields()
	{
		return [
			['type'=>'int', 'field'=>'id'],
			['type'=>'string', 'field'=>'username'],
			['type'=>'string', 'field'=>'password'],
			['type'=>'datetime', 'field'=>'lastLogin'],
		];
	}

	/**
	 * Finds a User using the username
	 * @param  string $username The unique identifier IncidentNr
	 * @return User 			The ClientSatisfaction object
	 */
	public static function findByUsername($username)
	{
		$model = new static;
	    $sql = $model->addWhere($model->createSelectQuery(), ['Username'=>$username]);
	    $result = $model->query($sql);
	    if ($result) {
	    	$model->populateModel($result);
	    	return $model;
	    } else {
	    	return NULL;
	    }
	}

	/**
	 * Validates the given password with the password of this model
	 * @param  string $password The password to validate
	 * @return boolean 
	 */
	public function validatePassword($password)
	{
		return password_verify($password, $this->password);
	}

	/**
	 * Hashes the given password using the PASSWORD_BCRYPT option of password_hash()
	 * @param  string $password The password to hash
	 * @return string 			The hashed password
	 */
	public function hashPassword($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	/**
	 * @return integer 
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return integer 
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

}