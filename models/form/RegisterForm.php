<?php
/**
 * Created by FesVPN.
 * @project Chamcong-org
 * @author  Pham Hai
 * @email   mitto.hai.7356@gmail.com
 * @date    4/11/2020
 * @time    12:31 AM
 */

namespace app\models\form;

use app\models\User;
use yii\base\Model;
use yii\helpers\Json;

class RegisterForm extends Model {

	//TODO create form login.
	public $username;

	public $password;

	public $email;

	public $confirPassword;

	public function rules() {
		// TODO: Change the auto generated
		return [
			[
				[
					'email',
					'username',
					'password',
					'confirPassword',
				],
				'required',
			],
			[
				'email',
				'email',
			],
			[
				['username'],
				'match',
				'pattern' => '/^[a-zA-Z0-9]+$/',
				'message' => 'Username can not contain special character',
			],
			[
				['password'],
				'match',
				'pattern' => '/^[a-zA-Z0-9]+$/',
				'message' => 'Password can not contain special character',
			],
			[
				'password',
				'string',
				'min' => 6,
			],
			[
				'confirPassword',
				'compare',
				'compareAttribute' => 'password',
			],
		];
	}

	public function register() {
		if ($this->validate()) {
			$user             = new User();
			$user->email      = $this->email;
			$user->username   = $this->username;
			$user->password   = md5($this->password);
			return $user->save();
		}
	}
}
