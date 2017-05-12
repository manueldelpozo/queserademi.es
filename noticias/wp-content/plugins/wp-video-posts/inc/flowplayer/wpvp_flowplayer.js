jQuery(window).load(function(){
	var wpvp_swf_location = object_name.swf;
	flowplayer("a.myPlayer", ""+wpvp_swf_location+"", { clip:{ autoPlay:false, autoBuffering:true }, plugins: { controls: { volume: true } }});
});
