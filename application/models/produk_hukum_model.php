<?php
class Produk_hukum_model extends CI_Model
{

	var $table = 'v2_produk_hukum';
	
	var $column_order = array(null,null,'v2_jenis_produk_hukum.nama_jenis_produk_hukum','nomor','tahun','nama_produk_hukum',null,null,null,null,null);
	var $column_search = array('v2_jenis_produk_hukum.nama_jenis_produk_hukum','nomor','tahun','nama_produk_hukum');
	var $order = array('id_produk_hukum' => 'desc'); // default order 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{	
		$this->db->select('v2_produk_hukum.*, v2_jenis_produk_hukum.nama_jenis_produk_hukum');
		$this->db->from($this->table);
		$this->db->join('v2_jenis_produk_hukum', 'v2_produk_hukum.id_jenis_produk_hukum=v2_jenis_produk_hukum.id_jenis_produk_hukum');
				
		if($this->input->post('id_jenis_produk_hukum'))
        {
            $this->db->where('v2_produk_hukum.id_jenis_produk_hukum', $this->input->post('id_jenis_produk_hukum'));
        }
				
		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					//$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
				{
					//$this->db->group_end(); //close bracket
				}
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_produk_hukum',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_produk_hukum()
	{
		$this->db->from($this->table);
		$query = $this->db->get();

		return $query->result();
	}	

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_produk_hukum', $id);
		$this->db->delete($this->table);
	}	

	public function get_list_produk_hukum()
    {
        $this->db->select('id_produk_hukum, nama_produk_hukum');
        $this->db->from($this->table);
        $this->db->order_by('nama_produk_hukum','asc');
        $query = $this->db->get();
        $result = $query->result();

        $produk_hukums = array();
        foreach ($result as $row) 
        {
            $produk_hukums[$row->id_produk_hukum] = $row->nama_produk_hukum;
        }
        return $produk_hukums;
    }			
}
		