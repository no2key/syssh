<?php
class Express extends SS_controller{

	function __construct(){
		parent::__construct();

		$this->load->library('pagination');
		$this->load->library('session');
	}

	function lists(){
		$field=array(
			'content'=>array('title'=>'寄送内容','surround'=>array('mark'=>'a','href'=>'/express/{id}'),'td'=>'class="ellipsis" title="{content}"'),
			'time_send'=>array('title'=>'日期','td_title'=>'width="60px"','eval'=>true,'content'=>"
				return date('m-d',{time_send});
			"),
			'sender_name'=>array('title'=>'寄送人'),
			'destination'=>array('title'=>'寄送地点','td'=>'class="ellipsis" title="{destination}"'),
			'num'=>array('title'=>'单号'),
			'comment'=>array('title'=>'备注')
		);
		
		$table=$this->express->getList($field);

		$this->data+=compact('table');
		
		$this->load->view('lists',$this->data);
	}
	
	function add(){
		$this->edit();
	}
	
	function edit($id=NULL){
		$this->getPostData($id,function($CI){
			global $_G;
			post('express/time_send',$CI->config->item('timestamp'));
		});
		
		$q_sender_name="SELECT name FROM staff WHERE id='".post('express/sender')."'";
		$r_sender_name=db_query($q_sender_name);
		post('express_extra/sender_name',mysql_result($r_sender_name,0,'name'));
		
		post('express_extra/time_send',date('Y-m-d',post('express/time_send')));
		
		$submitable=false;//可提交性，false则显示form，true则可以跳转
		
		if(is_posted('submit')){
			$submitable=true;
			$_SESSION[IN_UICE]['post']=array_replace_recursive($_SESSION[IN_UICE]['post'],$_POST);
			
			//将寄件人姓名转换成staff,id
			$q_staff="SELECT id,name FROM staff WHERE name LIKE '%".post('express_extra/sender_name')."%' LIMIT 2";
			$r_staff=db_query($q_staff);
			if(db_rows($r_staff)==0 || db_rows($r_staff)>1){
				showMessage('寄件人不是职员，或存在多个匹配','warning');
				$submitable=false;
			}else{
				post('express/sender',mysql_result($r_staff,0,'id'));
				post('express_extra/sender_name',mysql_result($r_staff,0,'name'));
			}
			
			//将时间转换成timestamp格式
			if(strtotime(post('express_extra/time_send'))){
				post('express/time_send',strtotime(post('express_extra/time_send')));
			}else{
				$submitable=false;
				showMessage('寄送日期格式错误','warning');
			}
			
			$this->processSubmit($submitable);
		}
	}
}
?>