<script src="/js/highcharts.js"></script>
<script src="/js/modules/exporting.js"></script>
<script>
	var hicharts_settings= {
		chart: {
			type: 'spline',
			backgroundColor: 'transparent'
		},
		title: {
			text: ''
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			type: 'datetime',
			dateTimeLabelFormats: { // don't display the dummy year
				millisecond: '%H:%M:%S.%L',
				second: '%H:%M:%S',
				minute: '%H:%M',
				hour: '%H:%M',
				day: '%e. %b',
				week: '%e. %m',
				month: '%b \'%y',
				year: '%Y'
			},
			title: {
				text: ''
			}
		},
		yAxis: {
			title: {
				text: ''
			},
			min: 0
		},
		tooltip: {
			headerFormat: '<b>{series.name}</b><br>',
			pointFormat: '{point.x:%e.%m.%Y}: {point.y:.2f} руб.'
		},
		legend: {
			enabled: false
		}
	};
</script>
<div class="good-card">
	<h2><?=$good['title']?></h2>
	Магазин: <b><?=$good['shop_title']?></b><br/>
	Ссылка: <b><?=Utils::link($good['url'],$good['url'])?></b><br />
	Парсинг: <b><?=$good['title']?"Да":"Нет"?></b><br/>
	Скрыт: <b><?=$good['hide']?"Да":"Нет"?></b><br/>
	Выборка цен от <b><?=$good['prices_from']?></b> до <b><?=$good['prices_to']?></b><br />

	<script type="text/javascript">

		$(function () {
			var set = hicharts_settings;
			set.series=[{

				name: '<?=addcslashes($good['title'], "'");?>',
				data: [
					<? foreach($good['prices'] as $price):?>
					<? if(intval($price['price'])>0):?>
					[Date.UTC(<?=substr($price['date'], 0, 4)?>,<?=intval(substr($price['date'], 5, 2))-1?>,<?=intval(substr($price['date'], 8, 2))?>),<?=floatval(str_replace(" ","",$price['price']))?>],
					<?endif;?>
					<?endforeach;?>
				]

			}]
			$('#plot<?=$good['id']?>').highcharts(set);
		});
	</script>
	<div id="plot<?=$good['id']?>" ></div>
	<?if(App::$user):?>
		<div class="parse">
			<div onclick="parse_price(<?=$good['id']?>, this)" class="parse-button">Спарсить цену</div>
			<div class="result"></div>
		</div>
	<?endif;?>
</div>