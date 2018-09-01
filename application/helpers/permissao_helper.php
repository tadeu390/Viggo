<?php 
	class permissao{
		public static function get_permissao($type,$modulo){
			$CI = get_instance();
			$CI->load->model("Geral_model");	
			return $CI->Geral_model->get_permissao($type,$modulo);
		}
	}
	
?>