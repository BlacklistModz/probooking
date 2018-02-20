<?php

class Agency_company_model extends Model  {

	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data)
	{
		if( empty($data['create_date']) ) $data['create_date'] = date('c');
		$data['status'] = 1;
		$data['agen_show'] = 1;

		$this->db->insert('agency_company', $data);
		return $this->db->lastInsertId();
	}

}