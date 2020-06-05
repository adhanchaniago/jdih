<?php
class User_akses_model extends CI_Model
{
	var $table = 'v2_user_akses';
	var $column_order = array('menu_sub_name','user_level_name','user_akses_aktif',null); //set column field database for datatable orderable
	var $column_search = array('menu_sub_name','user_level_name','user_akses_aktif',); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('user_akses_id' => 'desc'); // default order 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{	
		$this->db->from($this->table);
		$this->db->join('v2_menu_sub', 'v2_menu_sub.menu_sub_id=v2_user_akses.menu_sub_id');
		$this->db->join('v2_user_level', 'v2_user_level.user_level_id=v2_user_akses.user_level_id');
		$this->db->join('v2_menu', 'v2_menu_sub.menu_id=v2_menu.menu_id');
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
		$this->db->where('user_akses_id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	function get_menu($user_level_id)
	{
		$this->db->select('user_akses.user_akses_id, user_akses.menu_sub_id, user_akses.user_level_id, menu_sub.menu_id, menu.menu_name, menu.menu_link, menu.menu_class');
		$this->db->from($this->table);
		$this->db->join('menu_sub', 'user_akses.menu_sub_id=menu_sub.menu_sub_id');
		$this->db->join('menu', 'menu_sub.menu_id=menu.menu_id');
		$this->db->where('user_akses.user_level_id', $user_level_id);
		$this->db->group_by('menu_sub.menu_id');
		$query = $this->db->get();
		return $query;
	}

	function get_menu_sub($menu_id, $user_level_id)
	{
		$this->db->select('user_akses_id, user_akses.menu_sub_id, menu_sub.menu_id, menu_sub.menu_sub_name, menu_sub.menu_sub_link');
		$this->db->from($this->table);
		$this->db->join('menu_sub', 'user_akses.menu_sub_id=menu_sub.menu_sub_id');
		$this->db->where(array('menu_sub.menu_id' => $menu_id, 'user_akses.user_level_id' => $user_level_id));
		$this->db->group_by('user_akses.menu_sub_id');
		$query = $this->db->get();
		return $query;
	}

	public function cek_akses($user_level_id, $menu_sub_link)
	{
		if ($user_level_id > 1)
		{
			$this->db->select('user_akses.menu_sub_id, user_akses.user_level_id, menu_sub.menu_sub_link');
			$this->db->from($this->table);
			$this->db->join('menu_sub', 'user_akses.menu_sub_id=menu_sub.menu_sub_id');
			$this->db->where('user_akses.user_level_id', $user_level_id);
			$this->db->where('menu_sub.menu_sub_link', $menu_sub_link);

			$query = $this->db->get();
			$num_rows = $query->num_rows();
			if ($num_rows == 1)
				return TRUE;
			else
				return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function get_insert_akses($user_level_id, $menu_sub_link)
	{
		if ($user_level_id > 1)
		{
			$this->db->select('user_akses.menu_sub_id, user_akses.user_level_id, menu_sub.menu_sub_link');
			$this->db->from($this->table);
			$this->db->join('menu_sub', 'user_akses.menu_sub_id=menu_sub.menu_sub_id');
			$this->db->where('user_akses.user_level_id', $user_level_id);
			$this->db->where('menu_sub.menu_sub_link', $menu_sub_link);
			$this->db->where('user_akses.insert_data', 'Y');
			$query = $this->db->get();
			$num_rows = $query->num_rows();
			if ($num_rows == 1)
				return FALSE;
			else
				return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_update_akses($user_level_id, $menu_sub_link)
	{
		if ($user_level_id > 1)
		{
			$this->db->select('user_akses.menu_sub_id, user_akses.user_level_id, menu_sub.menu_sub_link');
			$this->db->from($this->table);
			$this->db->join('menu_sub', 'user_akses.menu_sub_id=menu_sub.menu_sub_id');
			$this->db->where('user_akses.user_level_id', $user_level_id);
			$this->db->where('menu_sub.menu_sub_link', $menu_sub_link);
			$this->db->where('user_akses.update_data', 'Y');
			$query = $this->db->get();
			$num_rows = $query->num_rows();
			if ($num_rows == 1)
				return FALSE;
			else
				return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_delete_akses($user_level_id, $menu_sub_link)
	{
		if ($user_level_id > 1)
		{
			$this->db->select('user_akses.menu_sub_id, user_akses.user_level_id, menu_sub.menu_sub_link');
			$this->db->from($this->table);
			$this->db->join('menu_sub', 'user_akses.menu_sub_id=menu_sub.menu_sub_id');
			$this->db->where('user_akses.user_level_id', $user_level_id);
			$this->db->where('menu_sub.menu_sub_link', $menu_sub_link);
			$this->db->where('user_akses.delete_data', 'Y');
			$query = $this->db->get();
			$num_rows = $query->num_rows();
			if ($num_rows == 1)
				return FALSE;
			else
				return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_user_akses($array="")
	{
		$this->db->from($this->table);
		if(is_array($array))
		{
			$this->db->where($array);
		}
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
		$this->db->where('user_akses_id', $id);
		$this->db->delete($this->table);
	}				
		
}