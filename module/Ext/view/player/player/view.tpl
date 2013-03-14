<script type="text/javascript">
$(document).ready(function(){
	$("#jquery_jplayer_1").jPlayer({
		ready: function() {
			$(this).jPlayer("setMedia", {
				mp3: "http://{{fileurl}}"
			}).jPlayer("play");
		},
		loop: true,
		swfPath: "http://lib.enorange.com/cms/v4/front/script/effect/player/Jplayer.swf"
	});
});
</script>
<div id="jquery_jplayer_1"></div>