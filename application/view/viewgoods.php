<script src="/js/highcharts.js"></script>
<script src="/js/modules/exporting.js"></script>
<div class="good-placer">
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
<?foreach($goods as $good):?>
	<div class="good-card" id="good<?=$good['id']?>">
		<div class="good-info">
			<div class="good-title"><a href="/viewgood/<?=$good['id']?>"><?=$good['title']?></a> <a href="#good<?=$good['id']?>">#</a></div>
			Выборка цен от <b><?=$good['prices_from']?></b> до <b><?=$good['prices_to']?></b><br />
			<?if(App::$user):?><a href="/editgood/<?=$good['id']?>">Изменить</a><br/><?endif;?>
			<img src="/data/shops/<?=$good['shop']?>/logo_50x50.png" />
		</div>
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
		<div id="plot<?=$good['id']?>" class="good-price-plot"></div>
	</div>
<?endforeach;?>

</div>