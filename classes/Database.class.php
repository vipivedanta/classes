<?php 
	
	class Database{

		private $host = 'localhost';
		private $user = 'root';
		private $password = '';
		private $database = 'clarity_us_v20_testing';
		public $conn = false;
		public $sql = [
						'data' => '',
						'count' => ''
					];

		public function __construct(){
			$this->connectDatabase();
		}

		private function connectDatabase(){
			$this->conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		}

		public function select($fields = ['*']){
			$this->sql['data'] .= 'select '.implode(',',$fields);
			$this->sql['count'] = $this->sql['data'];
			return $this;
		}

		public function from($table){
			$this->sql['data'] .= ' from '.$table;
			$this->sql['count'] = $this->sql['data'];
			return $this;
		}

		public function where($params){
			$conditions = [];
			foreach($params as $f => $v){
				$conditions[] = $f.' = '.$v;
			}

			if(!$this->isThereWhere())
				$this->sql .= ' where ';

			$this->sql .= implode(' and ', $conditions);
			return $this;
		}

		public function orWhere($params){
			$conditions = [];
			foreach($params as $f => $v){
				$conditions[] = $f.' = '.$v;
			}

			if(!$this->isThereWhere())
				$this->sql .= ' where ';

			$this->sql['data'] .= implode(' or ', $conditions);
			$this->sql['count'] = $this->sql['data'];
			return $this;
		}

		public function isThereWhere(){
			if(strpos($this->sql, 'where') > -1 )
				return true;
			return false;
		}

		public function groupBy($field){
			$this->sql['data'] .= ' group by '.$field;
			$this->sql['count'] = $this->sql['data'];
			return $this;
		}

		public function orderBy($field,$order = 'asc'){
			$this->sql['data'] .= ' order by '.$field.' '.$order;
			$this->sql['count'] = $this->sql['data'];
			return $this;
		}

		
		public function numrows(){
			$this->res = mysqli_query($this->conn,$this->sql['count']);
			return mysqli_num_rows($this->res);
		}

		public function get(){
			$this->res = mysqli_query($this->conn,$this->sql['data']);
			$records = [];
			while($row = mysqli_fetch_object($this->res)){
				$records[] = $row;
			}
			return $records;
		}

		public function limit($limit,$offset){
			$this->sql['data'] .= ' limit '.$limit.','.$offset;
			return $this;
		}

		public function paginate($perPage = 10){
			$this->perPage = $perPage;
			$this->limit(( isset($_GET['page'] )) ? intval($_GET['page']) : 0 , $perPage);
			return $this->get();
		}

		public function setPaginationUrl($url){
			$this->url = $url;
		}

		public function createLinks(){
			$this->totalRecords = $this->numrows();
			$this->totalPages = ceil( $this->totalRecords / $this->perPage );

			$this->links = '';
			for($i=1;$i<=$this->totalPages;$i++){
				$page = ($i * $this->perPage) - $this->perPage;

				if($i == 1) $page = 0;

				$currentPage = (isset($_GET['page'])) ? intval($_GET['page']) : 0;

				if($currentPage != $page)
					$this->links .= '<li><a href="'.$this->url.'?page='.$page.'">'.$i.'</a></li>';
				else
					$this->links .= '<li>'.$i.'</li>';
			}

			return '<ul>'.$this->links.'</ul>';
		}
	}
?>