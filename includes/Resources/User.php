<?php 

namespace WOAP\Resources;

use WOAP\Models\User;

class User extends User {
	
	public $have_map = [
		'Message' => WOAP\Models\Message::class
	];

	public $have_key = [
		'Message' => 'user_id'
	];

}
