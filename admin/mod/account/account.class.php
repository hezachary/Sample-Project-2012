<?php

class account extends user{
	const strTable = 'user';
	
	public function setId($id){
		$this->id = (int)$id;
	}
	
	public function retrieveId(){
		return $this->id;
	}
	
	public static function convertPassword($password){
		return sha1(base64_encode($password));
	}

	public function updatePw(){
		$this->convertPw();
		
		$arySet = array('password'	=> $this->password,
					);
		
		$aryWhere = $this->db->quoteInto('id = ?', $this->id);
		
		$this->db->update(self::strTable, $arySet, $aryWhere);
		
		$objSelect = $this->db->select();
		$objSelect->from(self::strTable, '*');
		$objSelect->where('id = ?', $this->id);
		$aryResult = $this->db->fetchRow($objSelect->__toString());
			
		self::setUser($aryResult);
	}
	
	protected function convertPw(){
		$this->password = sha1(base64_encode($this->pw_org));
		//$this->password = sha1(base64_encode('12345'));
		//echo $this->password;
	}
}

?>