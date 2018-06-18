<?php 
	echo $c['content'];
?>

<script>
var downloadBtn = document.getElementsByClassName('download_btn');

[].slice.call(downloadBtn).forEach(function(item) {
    item.addEventListener("click", function(e) {
		var trackInfo = document.getElementById('trackinfo');
		var a = document.createElement('a');
		var url = '../data/tracks/' + trackInfo.value;
		document.body.appendChild(a);
		a.style = 'display: none';
		a.href = url;
		a.download = trackInfo.value;
		a.click();
		window.URL.revokeObjectURL(url);
	});
});
</script>
