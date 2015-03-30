<div class="edit-good-box">
<form method="POST">

<label>
	Заголовок:
	<input type="text" name="title" value="<?=htmlspecialchars($good['title'])?>" />
</label><br />

<label>
	Магазин:
	<select name="shop">
		<?foreach($shops as $shop):?>
		 <option <?=($good['shop']==$shop['shop_id'])?'selected="selected"':"";?> value="<?=$shop['shop_id']?>"><?=$shop['shop_title']?></option>
		<?endforeach;?>
	</select>
</label><br />


<label>
	Ссылка:
	<input type="text" name="url" value="<?=htmlspecialchars($good['url'])?>" />
</label><br />

<label>
	Скрыть:
	<input type="checkbox" name="notshow" <?=($good['notshow'])?'checked="checked"':'';?> value="1" />
</label><br />

<label>
	Не парсить:
	<input type="checkbox" name="notparse" <?=($good['notparse'])?'checked="checked"':'';?> value="1" />
</label><br />

	<input type="submit" value="Ok" />
</form>



</div>