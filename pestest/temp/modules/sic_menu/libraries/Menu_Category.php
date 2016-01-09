<?php
class Menu_Category_Core {
	
	protected $table_name = 'menu_categories';
	protected $primary_key = 'menu_categories_id';
	protected $col_order = 'menu_categories_sort_order';
	protected $left_column = 'menu_categories_left';
	protected $right_column = 'menu_categories_right';
	protected $level_column = 'menu_categories_level';
    protected $col_language_id = 'languages_id';
    public $db;
    
    public function __construct()
    {
        $this->db = Database::instance();     
    }
    
    public function get($model='',$id='')
    {
        if($id) $this->db->where($this->table_name.'.'.$this->primary_key,$id);		
		$mlist = $model->orderby($this->left_column)->get();
		echo $this->db->last_query();		
		for($i=0; $i<count($mlist); $i++)
		{
			$model_lang = new Language_Model();	
			$list_lang = $model_lang->get_with_active();
			for($j=0; $j<count($list_lang); $j++)
			{
				$model_cat = new Menu_category_description_Model();		
				$list = $model_cat->get($mlist[$i][$this->primary_key],$list_lang[$j][$this->col_language_id]);
				if($list){
					$list = array_merge($mlist[$i],$list[0]);			
				} else
					$list = $mlist[$i];
				$list_lang[$j] = $list;
			}
			$mlist[$i]['languages'] = $list_lang;
		}
		return $mlist;
    }
}
?>