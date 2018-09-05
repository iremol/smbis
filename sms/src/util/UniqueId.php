<?php

namespace sms\util;
/**********************************************************************************/
// A class to generate a unique 20 characters id
class UniqueId {
	
	private $max_digit;
	private $id;
	private $rowid;
	
	private $query = "SELECT id FROM testing";
	
	private function generate($length = 5) {
		// generates the unique id using md5 sum
		$this->id = md5(uniqid(rand(), true));
		$this->max_digit= $length;
		$this->id = substr($this->id,0, $this->max_digit);
		return $this->id;
	}
        public function generateCandidateId() {
		// generates the unique id using md5 sum
		return strtoupper("C".date('Y').$this->generate());
	}
        public function generateApplicationId() {
		// generates the unique id using md5 sum
		return strtoupper("A".date('Y').$this->generate('7'));
	}
        public function generateStudentId() {
		// generates the unique id using md5 sum
		return strtoupper("R".date('Y').$this->generate('7'));
	}
        public function generateEmpId() {
		// generates the unique id using md5 sum
		return strtoupper("E".date('Y').$this->generate('5'));
	}
        
        
	/**
         * generateAttId
         * @return string
         */
        public function generateAttId(): string {
            // generates the unique id using md5 sum
            return strtoupper("ATT".date('Y').$this->generate('5'));
        }
	private function retrieveId() {
		
		
		if ( $result = mysqli_query($connectionInfo, $this->query) ) {
			$this->rowid = mysqli_fetch_array($result);
			return $this->rowid[0];
		}
	}
	
	private function checkId() {
		if ( $this->generate() == $this->retrieveId() )
			return false;
		else
			return true;
	}
		
	public function generateId_old() {
		do {
			$status = $this->checkId();
		}while ( $status == false );
		
		return $this->id;
		
	}
        public function generateId($length=5) {

		return $this->generate($length);
		
	}
}

?>