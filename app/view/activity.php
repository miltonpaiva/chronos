<?php header ('Content-type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	<title>contador de horas por atividade</title>
	<link rel="shortcut icon" href="c2.png" />
	<style>
		/*
		  Parts of this code is inspired from the following:
		  [1] https://userstyles.org/styles/127801/dark-night
		*/

		html {
		  filter: invert(100%) !important;
		  background-color: #131313 !important;
		  -webkit-filter: invert(100%) !important;
		}

		body {
		  background-color: #FFF !important;
		}

		img, video {
		  filter: invert(100%) !important;
		  -webkit-filter: invert(100%) !important;
		}


		td{
			min-width: 30px;
			height: auto;
			align-items: center;
			justify-content: center;
		}
		table, h1{
			margin: 10% auto;
			height: 50%;

			align-items: center;
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			justify-content: center;
		}
		button{
			margin-left: auto;
		}
	</style>
</head>
<body>
	<hr>

	<?php

		global $include_paths, $entity;

		$data_current_json =
			[
				'atividade_1' =>
					[
						'key' 			=> 'atividade_1',
						'time' 			=> '00:00:00',
						'running' 		=> 'false',
						'description' 	=> 'Atividade 1'
					]
			];

		$date = date("d_m_Y");

		$path_archive = "{$include_paths['json']}{$entity}/{$date}.json";
		$path_json = "{$include_paths['json_web']}{$entity}/{$date}.json";

		$current_archive = file_exists($path_archive);

		if ($current_archive) {
			$data_archive = file_get_contents($path_json);

			$data_current_json = json_decode($data_archive, true);
		}

		$cont = 0;
	?>

	<h1 id="data_atual"></h1>
	<table border="2">
		<tbody id="table_atividades">

			<tr>
				<td>Tempo</td>
				<td>Descricao</td>
				<td colspan="3">ações</td>
			</tr>

			<?php foreach ($data_current_json as $data_json): $cont++; ?>

					<tr>
						<td id_ref="atividade_<?= $cont; ?>" id="tempo_atividade_<?= $cont; ?>">

							<?= $data_json['time']; ?>

						</td>
						<td>
							<p id="descricao_atividade_<?= $cont; ?>" id_ref="atividade_<?= $cont; ?>" contenteditable="true">

								<?= $data_json['description']; ?>

							</p>
						</td>
						<td>
							<button id="iniciar_atividade_<?= $cont; ?>" id_ref="atividade_<?= $cont; ?>" class="iniciar_atividade" onclick="iniciar(this)" <?php echo ($data_json['running'] == 'true') ? 'disabled="true"' : '' ; ?> >Iniciar</button>
						</td>
						<td>
							<button id="pausar_atividade_<?= $cont; ?>" id_ref="atividade_<?= $cont; ?>" onclick="pausar(this)">Pausar</button>
						</td>
						<?php if ($cont == 1): ?>
							<td id="nova_atividade" rowspan="<?= count($data_current_json); ?>">
								<button onclick="novaAtividade(this)">+</button>
							</td>
						<?php endif ?>
					</tr>

			<?php endforeach ?>

		</tbody>
	</table>

	<hr>



	<hr>


	<?php

		global $include_paths, $entity;
		$dir = dir("{$include_paths['json']}{$entity}/");

		$data_json_existing;

		while($archive = $dir->read()){
			$valid_archive = strrpos($archive, '_');

				$path = "{$include_paths['json_web']}{$entity}/{$archive}";

			if ($valid_archive > -1) {

				$date = str_replace('.json', '', $archive);
				$date = str_replace('_', '/', $date);

				if ($date != date("d/m/Y")) {
					$data_archive = file_get_contents($path);

					$data_json_existing[$date] = json_decode($data_archive, true);
				}


	    	}
		}

	?>

	<?php foreach ($data_json_existing as $date_created => $data): ?>

			<h1><?= $date_created; ?></h1>
			<table border="2">
				<tbody>

					<tr>
						<td>Tempo</td>
						<td>Descricao</td>
					</tr>

					<?php foreach ($data as $value): ?>

							<tr>
								<td>
									<?= $value['time']; ?>
								</td>
								<td>
									<p><?= $value['description']; ?></p>
								</td>
							</tr>

					<?php endforeach ?>


				</tbody>
			</table>

			<hr>

	<?php endforeach ?>


	<script>

		var data = new Date();

		var dias_semana = 	[
								'Domingo',
								'Segunda',
								'Terça',
								'Quarta',
								'Quinta',
								'Sexta',
								'sabado'
							]

		var ano_atual = data.getFullYear();
		var mes_atual = (data.getMonth() + 1);
		var dia_atual = data.getDate();
		var dia_semana_atual = data.getDay();

		var str_data_atual = dias_semana[dia_semana_atual] + ' - ' + dia_atual + '/' + mes_atual + '/' + ano_atual;

		var h1_data_atual = document.getElementById('data_atual');

		h1_data_atual.innerHTML = str_data_atual;

		var numero_atividade = <?= count($data_current_json); ?>;

		var intervalos = {};

		var atividades = {};

		function iniciar(btn) {
			var id_ref = btn.getAttribute('id_ref');

			btn.disabled = true;

			btn.innerHTML = 'Iniciar'

			iniciaIntervalo(id_ref);

		}

		function iniciaIntervalo(id_ref) {
			var	td_tempo = document.getElementById('tempo_' + id_ref);

			var str_tempo = td_tempo.innerHTML;

			var arr_tempo = str_tempo.split(':');

			var hora = parseInt(arr_tempo[0]);
			var min = parseInt(arr_tempo[1]);
			var seg = parseInt(arr_tempo[2]);

			var compl_hora = '0';
			var compl_min = '0';
			var compl_seg = '0';


			intervalos[id_ref] = setInterval(function(){

				seg++;

				if (seg == 60) { min++; seg = 00; }

				if (min == 60) { hora++; min = 00; salvaAtividades(id_ref); }

				if (seg > 9) { compl_seg = ''; }else{ compl_seg = '0'; }

				if (min > 9) { compl_min = ''; }else{ compl_min = '0'; }

				if (hora > 9) { compl_hora = ''; }else{ compl_hora = '0'; }

				var str_seg = compl_seg + '' + seg;
				var str_min = compl_min + '' + min;
				var str_hora = compl_hora + '' + hora;

				td_tempo.innerHTML = str_hora + ':' + str_min + ':' + str_seg;

			}, 1000);
		}

		function salvaAtividades(id_ref) {

			var	td_tempo = document.getElementById('tempo_' + id_ref);
			var	p_descricao = document.getElementById('descricao_' + id_ref);
			var	btn_iniciar = document.getElementById('iniciar_' + id_ref);

			var chave_atividade = id_ref;
			var tempo = td_tempo.innerHTML;
			var descricao = p_descricao.innerHTML;

			var running = "false";
			if (btn_iniciar.disabled) {
				running = "true"
			}

			enviaDados(tempo, descricao, running, chave_atividade)
		}

		function enviaDados(tempo, descricao, running, chave) {

			var url_origem_atual = window.location.origin + window.location.pathname;

			var xhr = new XMLHttpRequest();

			var url_requisicao = url_origem_atual + "/save-activity";

			url_requisicao += '?t=' + tempo;
			url_requisicao += '&d=' + descricao;
			url_requisicao += '&r=' + running;
			url_requisicao += '&k=' + chave;

			xhr.open("GET", url_requisicao, true);
			xhr.onload = function (e) {
			  if (xhr.readyState === 4) {
			    if (xhr.status === 200) {
			      console.log(xhr.responseText);
			    } else {
			      console.error(xhr.statusText);
			    }
			  }
			};
			xhr.onerror = function (e) {
			  console.error(xhr.statusText);
			};
			xhr.send(null);
		}

		function pausar(btn) {
			var id_ref = btn.getAttribute('id_ref');

			var	btn_iniciar = document.getElementById('iniciar_' + id_ref);

			btn_iniciar.disabled = false;

			btn_iniciar.innerHTML = 'Retomar'

			clearInterval(intervalos[id_ref]);


			var	td_tempo = document.getElementById('tempo_' + id_ref);
			var	p_descricao = document.getElementById('descricao_' + id_ref);

			var chave_atividade = id_ref;
			var tempo = td_tempo.innerHTML;
			var descricao = p_descricao.innerHTML;

			var running = "false";

			enviaDados(tempo, descricao, running, chave_atividade)

		}

		function novaAtividade() {

			numero_atividade++

			var table_atividades = document.getElementById('table_atividades');

			var btn_nova_atividade = document.getElementById('nova_atividade');

			btn_nova_atividade.setAttribute('rowspan', numero_atividade);

			var html = table_atividades.innerHTML;

			html +='		<tr>';
			html +='			<td class="tempo_atividade" id_ref="atividade_' + numero_atividade + '" id="tempo_atividade_' + numero_atividade + '"> 00:00:00 </td>';
			html +='			<td>';
			html +='				<p id="descricao_atividade_' + numero_atividade + '" id_ref="atividade_' + numero_atividade + '" contenteditable="true">Atividade ' + numero_atividade + '</p>';
			html +='			</td>';
			html +='			<td>';
			html +='				<button id="iniciar_atividade_' + numero_atividade + '" id_ref="atividade_' + numero_atividade + '" class="iniciar_atividade" onclick="iniciar(this)">Iniciar</button>';
			html +='			</td>';
			html +='			<td>';
			html +='				<button id="pausar_atividade_' + numero_atividade + '" id_ref="atividade_' + numero_atividade + '" onclick="pausar(this)">Pausar</button>';
			html +='			</td>';
			html +='		</tr>';

			table_atividades.innerHTML = html;

			autoRetomada();
		}

		function autoRetomada() {
			var btns_inicio = document.getElementsByClassName('iniciar_atividade');
			for (var i = 0; i < btns_inicio.length; i++) {
				var btn = btns_inicio[i];
				if (btn.disabled) {
					iniciar(btn);
				}
			}
		}

		window.onload(autoRetomada());
	</script>
</body>
</html>