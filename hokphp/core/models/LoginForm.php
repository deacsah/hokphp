<?php 

namespace hokphp\core\models;

use hokphp\core\components\Model as BaseModel;
use hokphp\core\models\User;

/**
* ClientSatisfaction class
*/
class LoginForm extends BaseModel
{
	/**
	 * The username field
	 * @var string
	 */
	public $username;

	/**
	 * The password field
	 * @var string
	 */
	public $password;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			'username'=>[
				'required'=>true,
			],
			'password'=>[
				'required'=>true,
				'function'=>'validatePassword',
			],
		];
	}

	/**
	 * Validates the given fields
	 * @param  array $fields  	The fields to validate
	 * @return boolean			Wether all fields are valid
	 */
	public function validate($fields)
	{
		foreach ($fields as $field) {
			$rules = $this->rules();
			if (array_key_exists($field, $rules) && isset($this->$field)) {
				foreach ($rules[$field] as $ruleName => $ruleValue) {

					$fieldsArray = static::getFields();
					$fieldArrayKey = array_search($field, array_column($fieldsArray, 'field'));

					$fieldName = isset($fieldArrayKey) ? $fieldsArray[$fieldArrayKey]['veld'] : 'waarde';

					if ($ruleName == 'required' && $ruleValue === TRUE) {
						if (empty($this->$field)) {
							$this->errors[$field][] = 'Geen '.$fieldName.' opgegeven.';
						}
					}
					if ($ruleName == 'function') {
						if (!$this->$ruleValue()) {
							$this->errors[$field][] = 'Inloggen mislukt. Voer een geldige gebruikersnaam en wachtwoord in.';
						}
					}
				}
			}
		}
		return !empty($this->errors) ? FALSE : TRUE;
	}

	/**
	 * Logs the user in by creating an authenticated session
	 * @return boolean 	Wether the user was logged in
	 */
	public function login()
	{
		$_SESSION['username'] = $this->username;
		return true;
	}

	/**
	 * Validates the given username and password
	 * @return boolean 	Wether the username and password are validated
	 */
	public function validatePassword()
	{
		$user = User::findByUsername($this->username);
		if ($user) {
			if($user->validatePassword($this->password)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function getFields()
	{
		return [
			['type'=>'string', 'field'=>'username', 'veld'=>'gebruikersnaam'],
			['type'=>'string', 'field'=>'password', 'veld'=>'wachtwoord'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function tableName()
	{
		return false;
	}
}