<?php
	class SQL {
		private $db;

		private function __construct() {			
			$this->db = DB::Instance();
		}
		                            
		public function Select($table, $where_key = false, $where_value = false, $fetchAll = false){
			
			if ($where_key AND $where_value) {
				$query = "SELECT * FROM " . $table . " WHERE " . $where_key . " = '" . $where_value . "'";
			} else {
				$query = "SELECT * FROM " . $table;
			}

			$q = $this->db->prepare($query);
			$q->execute();
			
			if ($q->errorCode() != PDO::ERR_NONE) {
				$info = $q->errorInfo();
				die($info[2]);
			}

			if ($fetchAll) {
				return $q->fetchAll();
			} else if ($where_key AND $where_value) {
				return $q->fetch();
			} else {
				return $q->fetchAll();
			}
		}

		//"SELECT order_id, product_id, count, title, price FROM basket AS T1 INNER JOIN products AS T2 ON T1.product_id = T2.id WHERE T1.user_id = " . $_SESSION["user_id"]
		
		public function Insert($table, $object) {
			
			$columns = array();
			
			foreach ($object as $key => $value) {
			
				$columns[] = $key;
				$masks[] = ":$key";
				
				if ($value === null) {
					$object[$key] = 'NULL';
				}
			}
			
			$columns_s = implode(',', $columns);
			$masks_s = implode(',', $masks);
			
			$query = "INSERT INTO $table ($columns_s) VALUES ($masks_s)";
			
			$q = $this->db->prepare($query);
			$q->execute($object);
			
			if ($q->errorCode() != PDO::ERR_NONE) {
				$info = $q->errorInfo();
				die($info[2]);
			}
			
			return $this->db->lastInsertId();
		}
		
		public function Update($table, $object, $where) {
			
			$sets = array();
			 
			foreach ($object as $key => $value) {
				
				$sets[] = "$key=:$key";
				
				if ($value === NULL) {
					$object[$key]='NULL';
				}
			 }
			 
			$sets_s = implode(',',$sets);
			$query = "UPDATE $table SET $sets_s WHERE $where";

			$q = $this->db->prepare($query);
			$q->execute($object);

			if ($q->errorCode() != PDO::ERR_NONE) {
				$info = $q->errorInfo();
				die($info[2]);
			}
			
			return $q->rowCount();
		}
		
		
		public function Delete($table, $where) {
			
			$query = "DELETE FROM $table WHERE $where";
			$q = $this->db->prepare($query);
			$q->execute();
			
			if ($q->errorCode() != PDO::ERR_NONE) {
				$info = $q->errorInfo();
				die($info[2]);
			}
			
			return $q->rowCount();
		}

		public function Password ($name, $password) {
			
			return strrev(md5($name)) . md5($password);
		}
	}
