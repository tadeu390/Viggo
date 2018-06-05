<?php
	class paginacao
	{
		public static function get_paginacao($paginacao,$controller)
		{
			$qtd_paginas = $paginacao['size'] / $paginacao['itens_por_pagina'];
			//echo $paginacao['size'];
			$method = "index";

			if(isset($paginacao['method']))
				$method = $paginacao['method'];

			$parameter = "";//caso haja algum parametro na tela de listagem
			if(isset($paginacao['parameter']))
				$parameter = $paginacao['parameter']."/";

			if(ceil($qtd_paginas) > 1)
			{
				$filtros = "";
				//VERIFICAR SE HÁ FILTROS
				if(!empty($paginacao['filter']))
					$filtros = $paginacao['filter'];
				/////

				$inicio = $paginacao['pg_atual'] - 2;
				
				$offset = 0;
				if($inicio < 1)
				{
					$offset = $inicio * (-1);
					$inicio = 1;
				}
				else
					$inicio = $inicio + 1;
				
				if((ceil($qtd_paginas) - $paginacao['pg_atual']) + 1 < 5)
					$inicio = $paginacao['pg_atual'] - (5 - (ceil($qtd_paginas) - $paginacao['pg_atual'] + 1));
				
				if($inicio <= 1)
					$inicio = 1;
				
				$fim = $paginacao['pg_atual'] + $offset + 3;
				
				if($fim > ceil($qtd_paginas))
					$fim = ceil($qtd_paginas);
				echo"<div class='col-lg-8 offset-lg-4 col-sm-6 offset-sm-3 col-10 offset-1'>";
					echo"<nav aria-label='Page navigation'>";
						echo"<ul class='pagination'>";
							echo"<li class='page-item'>";
								if(!empty($paginacao['pg_atual']) && $paginacao['pg_atual'] > 1)						
									echo"<a class='page-link disabled' href='".$paginacao['url'] . $controller."/".$method."/".$parameter. ($paginacao['pg_atual'] - 1).$filtros."' aria-label='Previous'><span class='glyphicon glyphicon-menu-left'></span>&nbsp;</a>";
							echo"</li>";
							for($i = $inicio; $i <= $fim; $i++)
							{
									$active = "";
								if($i == $paginacao['pg_atual'])
									$active = "active";
								echo"<li class='page-item ".$active."'>";
									echo"<a class='page-link' href='".$paginacao['url'] . $controller."/".$method."/".$parameter.$i.$filtros."'>".$i."</a>";
								echo"</li>";
							}
							echo"<li class='page-item'>";
								if($paginacao['pg_atual'] < ceil($qtd_paginas))
									echo"<a class='page-link' href='".$paginacao['url'] . $controller."/".$method."/".$parameter. ($paginacao['pg_atual'] + 1).$filtros."' aria-label='Next'><span class='glyphicon glyphicon-menu-right'></span>&nbsp;</a>";
							echo"</li>";
						echo"</ul>";
					echo"</nav>";
				echo "</div>";
			}
		}
	}
?>