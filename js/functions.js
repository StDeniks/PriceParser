function parse_price(id, elem){
   $.get("/parse.php?id="+id, function(data){
       $(elem).parent().find(".result").html(data)
    });
}