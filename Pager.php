<?php
/**
 * 分页样式
 * #pager ul { list-style: none; }
 * #pager li { display: inline-block; }
 * #pager a { padding: 6px; }
 * #pager a.active { color: #666; }
 * #pager a:hover { color: #666; text-decoration: none; }
 * #pager a.disable {}
 * ========================
 * 调用方法
 * $page = new Pager($total, $page, array('limit'=>10));
 * $page->show();
*/

class Pager
{
	public $total; 	//总条数
	public $curr; 	//当前页
	public $url 	= null;
	private $options = array(
		'limit' 	=> 10, 			//每页显示条数
		'groups' 	=> 5, 			//每次显示多少页
		'first' 	=> '首页',		//首页名称
		'last'		=> '末页',		//末页名称
		'prev' 		=> '上一页', 	//上一页
		'next' 		=> '下一页', 	//下一页
		'page_name'	=> 'p'			//页的名称，?list=curr
 	);
 	/**
 	 * 构造函数
 	 * @param [num] $total [总数据条数]
 	 * @param [num] $page  [当前页数]
 	 * @param array  $options  [配置参数]
 	 */
    public function __construct($total, $page, $options = array()) {
    	$this->total 		= $total;
    	$this->curr 		= $page;
    	$this->options 		= array_merge($this->options, $options);
    	$this->total_pages 	= ceil($total / $this->options['limit']);
    	$this->plus 		= ceil(($this->options['groups'] - 1) / 2);
    }
    /**
     * 根据页数和文本获取链接
     */
    private function _get_link($page, $text) {
        if($page < 1 || $page > $this->total_pages) {
            $link   = '<a class="disable">' . $text . '</a>';
        } else {
            $link = $this->curr == $page ? '<a class="active">' . $text . '</a>' : '<a href="' . $this->_get_url($page) . '">' . $text . '</a>';
        }
    	return '<li>' . $link . '</li>';
    }

    /**
     * 设置当前页面链接
     */
    private function _set_url() {
        $url  =  $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') ? '' : "?");
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'], $params);
            unset($params[$this->options['page_name']]);
            $url   =  $parse['path'] . '?' . http_build_query($params);
        }
        if(!empty($params))
        {
            $url .= '&';
        }
        $this->url = $url;
    }
    /**
     * 得到页的链接
     */
    private function _get_url($page) {
        if($this->url === NULL)
        {
            $this->_set_url();   
        }
        return $this->url . $this->options['page_name'] . '=' . $page;
    }
    /**
     * 返回第一页链接
     */
    public function first () {
		return $this->_get_link(1, $this->options['first']);
    }
    /**
     * 返回最后一页链接
     */
    public function last () {
        return $this->_get_link($this->total_pages, $this->options['last']);
    }
    /**
     * 返回上一页链接
     */
    public function prev () {
		return $this->_get_link($this->curr - 1, $this->options['prev']);
    }
    /**
     * 返回下一页链接
     */
    public function next () {
		return $this->_get_link($this->curr + 1, $this->options['next']);
    }
    /**
     * 返回总记录条数
     */
    public function total () {
        return '<li><a class="total">总共<span class="num">'.$this->total.'</span>条记录</a></li>';
    }
    /**
     * 返回当前页 1/12
     */
    public function curr () {
        return '<li><a class="curr">当前页<span class="num">'.$this->curr.'/'.$this->total_pages.'</span></a></li>';
    }
    /**
     * 分页输出
     */
    public function show($params = 1) {
    	if($this->total < 1) {
    		return '';
    	}
    	$method_name = 'view_' . $params;
    	if(method_exists($this, $method_name)) {
    		return call_user_func(array($this, $method_name));
    	}
    	return '';
    }

    public function view_1 () {
    	$view 		= array();

        $curr       = $this->curr;
        $total_pages= $this->total_pages;
        $plus       = $this->plus;
        $groups     = $this->options['groups'];
        $limit      = $this->options['limit'];

        //当前页超过页组时显示首页
        if($curr > $groups) {
    	   $view[] 	= $this->first();
        }
        //当前页大于1时显示上一页
        if($curr > 1) {
    	   $view[] 	= $this->prev();
        }
    	
    	$begin 		= $plus + $curr > $total_pages ? $total_pages - $groups + 1 : $curr - $plus;
    	$begin 		= $begin >= 1 ? $begin : 1;
    	$end 		= $begin + $groups > $total_pages ? $total_pages + 1: $begin + $groups;
    	for ($i = $begin; $i < $end; $i++) {
    		$view[] 	= $this->_get_link($i, $i);
    	}
        //当前不是最后一页时显示下一页
        if($curr < $total_pages) {
    	   $view[] 	= $this->next();
        }
        //总页数超过页组且剩余页数超过偏移量时显示末页
        if($total_pages > $groups && $curr < $this->total_pages - $plus) {
    	   $view[] 	= $this->last();
        }

    	return '<ul>' . implode('', $view) . '</ul>';
    }

    public function view_2 () {
        $view       = array();
        $view[]     = $this->total();
        $view[]     = $this->first();
        $view[]     = $this->prev();
        $view[]     = $this->next();
        $view[]     = $this->last();
        $view[]     = $this->curr();

        return '<ul>' . implode('', $view) . '</ul>';
    }
}