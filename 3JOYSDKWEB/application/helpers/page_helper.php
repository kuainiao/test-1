<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 固化样式的分页函数
 *
 * @access	public
 * @param	array
 * @param	bool
 * @return	string
 */
if ( ! function_exists('page_html')){
	function page_html($base_url, $total_rows, $per_page, $uri_segment = 3){
		$CI =& get_instance();
		$CI->load->library('pagination');
                $config ['base_url'] = site_url($base_url);
                $config ['total_rows'] = $total_rows; //总条数
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['next_link'] = '下一页';
                $config['prev_link'] = '上一页';
                $config['cur_tag_open'] = '<em>'; // 当前页开始样式
                $config['cur_tag_close'] = '</em>'; // 当前页结束样式
                $config['per_page'] = $per_page;
		$config['uri_segment'] = $uri_segment;
                $config['num_links'] = 9;
                $config['anchor_class'] = ''; 
                
                $CI->pagination->initialize($config); 
                return $CI->pagination->create_links();
	}
}



// ------------------------------------------------------------------------

/* End of file page_helper.php */
/* Location: ./system/helpers/page_helper.php */