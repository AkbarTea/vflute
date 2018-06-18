window.onload = init;
var context;
var soundManager;
var offset = 1;

function init() {
  // Fix up prefixing
  window.AudioContext = window.AudioContext || window.webkitAudioContext;
  context = new AudioContext();

  soundManager = new SoundManager (
    context,
    [
    	'../static/sounds/do1.wav',
    	'../static/sounds/do-diez.wav',
    	'../static/sounds/re.wav',
    	'../static/sounds/re-diez.wav',
    	'../static/sounds/mi.wav',
    	'../static/sounds/fa.wav',
    	'../static/sounds/fa-diez.wav',
    	'../static/sounds/sol.wav',
    	'../static/sounds/sol-diez.wav',
    	'../static/sounds/lya.wav',
    	'../static/sounds/lya-diez.wav',
    	'../static/sounds/si.wav',
    	'../static/sounds/si-diez.wav',
    	'../static/sounds/do2.wav',
    ],
    null
    );
  	soundManager.load();
}

/*Buffer loader*/
function SoundManager (context, urlList, callback) {
  this.context = context;
  this.urlList = urlList;
  this.onload = callback;
  this.bufferList = new Array();
  this.sourceList = new Array();
  this.loadCount = 0;
}

SoundManager.prototype.loadBuffer = function(url, index) {
  // Load buffer asynchronously
  var request = new XMLHttpRequest();
  request.open("GET", url, true);
  request.responseType = "arraybuffer";

  var loader = this;

  request.onload = function() {
    // Asynchronously decode the audio file data in request.response
    loader.context.decodeAudioData(
      request.response,
      function(buffer) {
        if (!buffer) {
          alert('error decoding file data: ' + url);
          return;
        }
        var source = context.createBufferSource();
		source.buffer = buffer;
		source.connect(context.destination);
        
        loader.sourceList[index] = source;
        loader.bufferList[index] = buffer;

        if (++loader.loadCount == loader.urlList.length)
          loader.onload(loader.bufferList);
      },
      function(error) {
        console.error('decodeAudioData error', error);
      }
    );
  }

  request.onerror = function() {
    alert('SoundManager : XHR error');
  }

  request.send();
}

SoundManager.prototype.load = function() {
  for (var i = 0; i < this.urlList.length; ++i)
  	this.loadBuffer(this.urlList[i], i);
}



var chunks = [];
var mainBlob = null;
class Sound {

	constructor(context, type) {
		this.context = context;
		if (type == 'record') {
			this.context = new AudioContext();
			this.type = 'record';
			this.destination = this.context.createMediaStreamDestination();
			this.mediaRecorder = new MediaRecorder(this.destination.stream);
		} else {
			this.type = 'play';
			this.destination = context.destination;
		}
	}

	init() {
		this.oscillator = this.context.createOscillator();
		this.gainNode = this.context.createGain();

		this.oscillator.type = "sine";
		this.oscillator.connect(this.gainNode);
		this.gainNode.connect(this.destination);


		if (this.type == 'record') {
			this.mediaRecorder.ondataavailable = function(evt) {
			   // push each chunk (blobs) in an array
			   console.log(evt.data);
			   chunks.push(evt.data);
			};
			this.mediaRecorder.onstop = function(evt) {
			   // Make blob out of our blobs, and open it.
			   console.log(chunks.length);
			   for (var i = 0; i < chunks.length; i++) {
				   console.log(chunks[i]);
			   }
			   var blob = new Blob(chunks, { 'type' : 'audio/ogg; codecs=opus' });
			   document.querySelector("audio").src = URL.createObjectURL(blob);
			   //upload blob on server
			   mainBlob = blob;
			   // saveBlob(blob);
			};
		}
	}

	startRecord() {
		if (this.type == 'record') {
			this.mediaRecorder.start();
		}
	}

	stopRecord() {
		if (this.type == 'record') {
			this.mediaRecorder.stop();
		}
	}

	play(value, time) {
		this.init();
		this.oscillator.frequency.value = value;
		this.gainNode.gain.setValueAtTime(1, this.context.currentTime);
		
		this.oscillator.start(time);
		this.stop(time + 1);
		
	}

	stop(time) {
		this.gainNode.gain.exponentialRampToValueAtTime(0.001, time);
		this.oscillator.stop(time);

	}

	init_buffer() {
		this.source = this.context.createBufferSource();
		this.gainNode = this.context.createGain();

		this.source.connect(this.gainNode);
		this.gainNode.connect(this.destination);


		if (this.type == 'record') {
			this.mediaRecorder.ondataavailable = function(evt) {
			   // push each chunk (blobs) in an array
			   console.log(evt.data);
			   chunks.push(evt.data);
			};
			this.mediaRecorder.onstop = function(evt) {
			   // Make blob out of our blobs, and open it.
			   console.log(chunks.length);
			   for (var i = 0; i < chunks.length; i++) {
				   console.log(chunks[i]);
			   }
			   var blob = new Blob(chunks, { 'type' : 'audio/ogg; codecs=opus' });
			   document.querySelector("audio").src = URL.createObjectURL(blob);
			   //upload blob on server
			   mainBlob = blob;
			   // saveBlob(blob);
			};
		}
	}

	play_buffer(index, time) {
		this.init_buffer();

		this.source.buffer = soundManager.bufferList[index];
		this.gainNode.gain.setValueAtTime(1, this.context.currentTime);

		this.source.start(time);
		// this.stop_buffer(time + offset);
	}

	stop_buffer(time) {
		// this.gainNode.gain.exponentialRampToValueAtTime(0.001, time);
		// this.gainNode.gain.setValueAtTime(0, 2);
		// this.gainNode.gain.exponentialRampToValueAtTime(0.00001, time+1);
		this.source.stop(time + 0.28);
	}
}


// FLUTE
// var Notes = {
// 	C4 : 240,
// 	D4 : 480,
// 	E4 : 720,
// 	F4 : 960,
// 	G4 : 1200,
// 	A4 : 1680,
// 	B4 : 1920
// }

// var Notes = {
// 	C4 : 240,
// 	D4 : 480,
// 	E4 : 720,
// 	F4 : 960,
// 	G4 : 1200,
// 	A4 : 1680,
// 	B4 : 1920
// }

// PIANO
// var Notes = {
// 	C4 : 261.63,
// 	D4 : 293.66,
// 	E4 : 329.63,
// 	F4 : 349.23,
// 	G4 : 392.00,
// 	A4 : 440.00,
// 	B4 : 493.88
// }

//FLUTE
var Notes = {
	do : 0,
	do_diez : 1,
	re : 2,
	re_diez : 3,
	mi : 4,
	fa : 5,
	fa_diez : 6,
	sol : 7,
	sol_diez : 8,
	lya : 9,
	lya_diez : 10,
	si : 11,
	si_diez : 12,
	do_2 : 13
}

var Keys = {
	Q : "Q".charCodeAt(0),
	W : "W".charCodeAt(0),
	E : "E".charCodeAt(0),
	R : "R".charCodeAt(0),
	T : "T".charCodeAt(0),
	Y : "Y".charCodeAt(0),
	U : "U".charCodeAt(0),
	I : "I".charCodeAt(0),
	one : 49,
	two : 50,
	three : 51,
	four : 52,
	five : 53,
	six : 54,
	seven : 55,
	eight : 56,
	nine : 57
}

var playContext = new (window.AudioContext || window.webKitAudioContext)();
var recordContext = new AudioContext();
var note = new Sound(playContext, 'play');
var noteRecord = new Sound(recordContext, 'record');
// -------------------------------
var recordButton = document.getElementById('record_button');
var saveButton = document.getElementById('save_button');
var downloadButton = document.getElementById('download_button');
var clicked = false;

recordButton.addEventListener("click", function(e) {
   if (!clicked) {
   	   // noteRecord = new Sound(recordContext, 'record');
   	   noteRecord.startRecord();
       e.target.innerHTML = "Stop recording";
       clicked = true;
     } else {
       noteRecord.stopRecord();
       e.target.innerHTML = "Recorded";
       e.target.style.backgroundColor = "#F41136";
       e.target.disabled = true;
     }
});

saveButton.addEventListener("click", function(e) {
 	if (mainBlob != null) {
 		var trackName = prompt("Enter name of tarck");
 		saveBlob(mainBlob, trackName);
 	}
});

downloadButton.addEventListener("click", function(e) {
 	if (mainBlob != null) {
 		//download blob to client
	   var a = document.createElement('a');
	   var url = URL.createObjectURL(mainBlob);
	   document.body.appendChild(a);
	   a.style = 'display: none';
	   a.href = url;
	   a.download = 'yourTrack.wav';
	   a.click();
	   window.URL.revokeObjectURL(url);
 	}
});

// window.addEventListener("keydown", listenerKeyDown); 
window.addEventListener("keydown", listenerKeyDown_buffer);
window.addEventListener("keyup", listenerKeyUp_buffer);

function listenerKeyDown(event) {
	let now = playContext.currentTime;

	switch(event.keyCode) {
		case 49:
			note.play(Notes.C4, now);
			noteRecord.play(Notes.C4, now);
			blickAnimation("0");
			break;
		case 50:
			note.play(Notes.D4, now);
			noteRecord.play(Notes.D4, now);
			blickAnimation("1");
			break;
		case 51:
			note.play(Notes.E4, now);
			noteRecord.play(Notes.E4, now);
			blickAnimation("2");
			break;
		case 52:
			note.play(Notes.F4, now);
			noteRecord.play(Notes.F4, now);
			blickAnimation("3");
			break;
		case 53:
			note.play(Notes.G4, now);
			noteRecord.play(Notes.G4, now);
			blickAnimation("4");
			break;
		case 54:
			note.play(Notes.A4, now);
			noteRecord.play(Notes.A4, now);
			blickAnimation("5");
			break;
		case 55:
			note.play(Notes.B4, now);
			noteRecord.play(Notes.B4, now);
			blickAnimation("6");
			break;
	}
}

var keyPressed = false;

function playAndRecord(id) {
	let now = playContext.currentTime;
	note.play_buffer(id, now);
	noteRecord.play_buffer(id, now);
}

function listenerKeyDown_buffer(event) {
	let now = playContext.currentTime;

	if (!keyPressed) {
		switch(event.keyCode) {
			case Keys.Q:
				playAndRecord(Notes.do);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("2");
				blickAnimation("8");
				keyPressed = true;
				break;
			case Keys.two:
				playAndRecord(Notes.do_diez);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("3");
				blickAnimation("4");
				blickAnimation("5");
				blickAnimation("8");
				keyPressed = true;
				break;
			case Keys.W:
				playAndRecord(Notes.re);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("8");
				keyPressed = true;
				break;
			case Keys.three:
				playAndRecord(Notes.re_diez);

				blickAnimation("0");
				blickAnimation("2");
				blickAnimation("3");
				blickAnimation("8");
				keyPressed = true;
				break;
			case Keys.E:
				playAndRecord(Notes.mi);

				blickAnimation("0");
				blickAnimation("8");
				keyPressed = true;
				break;
			case Keys.R:
				playAndRecord(Notes.fa);

				blickAnimation("1");
				blickAnimation("8");
				keyPressed = true;
				break;
			case Keys.five:
				playAndRecord(Notes.fa_diez);
				
				blickAnimation("0");
				blickAnimation("1");
				keyPressed = true;
				break;
			case Keys.T:
				playAndRecord(Notes.sol);

				blickAnimation("1");
				keyPressed = true;
				break;
			case Keys.six:
				playAndRecord(Notes.sol_diez);

				blickAnimation("1");
				blickAnimation("2");
				blickAnimation("3");
				blickAnimation("4");
				blickAnimation("5");
				keyPressed = true;
				break;
			case Keys.Y:
				playAndRecord(Notes.lya);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("2");
				blickAnimation("3");
				blickAnimation("4");
				blickAnimation("8"); //half
				keyPressed = true;
				break;
			case Keys.seven:
				playAndRecord(Notes.lya_diez);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("2");
				blickAnimation("3");
				blickAnimation("8"); //half
				keyPressed = true;
				break;
			case Keys.U:
				playAndRecord(Notes.si);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("2");
				blickAnimation("4");
				blickAnimation("6");
				blickAnimation("8"); //half
				keyPressed = true;
				break;
			case Keys.I:
				playAndRecord(Notes.si_diez);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("2");
				blickAnimation("8"); //half
				keyPressed = true;
				break;
			case Keys.nine:
				playAndRecord(Notes.do_2);

				blickAnimation("0");
				blickAnimation("1");
				blickAnimation("3");
				blickAnimation("8"); //half
				keyPressed = true;
				break;
		}
	}
}

function listenerKeyUp_buffer(event) {
	let now = playContext.currentTime;
	if (keyPressed) {
		blickAnimationStop();
		switch(event.keyCode) {
			case Keys.Q:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);
				keyPressed = false;
				break;
			case Keys.two:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.W:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.three:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.E:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.R:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.five:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.T:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				blickAnimationStop();
				keyPressed = false;
				break;
			case Keys.six:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.Y:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.seven:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.U:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.I:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
			case Keys.nine:
				note.stop_buffer(now);
				noteRecord.stop_buffer(now);

				keyPressed = false;
				break;
		}
	}
}

// function blickAnimation(id) {
// 	let hole = document.getElementById(id);
// 	hole.style.backgroundColor = "#22FF33";
// 	setTimeout(function() {
// 		let hole = document.getElementById(id);
// 		hole.style.backgroundColor = "white";
// 	}, 200)
// }

function blickAnimation(id) {
	let hole = document.getElementById(id);
	hole.style.backgroundColor = "#22FF33";
}

function blickAnimationStop() {
	var holes = document.getElementsByClassName('hole');
		setTimeout(function() {
		for (var i = 0; i < holes.length; i++) {
			if (i != 7) {
				holes[i].style.backgroundColor = "white";
			}
		}
	}, 1);
}

function paintHole(id, color) {
	let hole = document.getElementById(id);
	hole.style.backgroundColor = color;
}

runOnKeys(
	function() { 
		let now = context.currentTime;
		note.play(Notes.A4, now);
		blickAnimation("5");
	},
	"Q".charCodeAt(0),
	"W".charCodeAt(0)
);

function runOnKeys(func, ...args) {
  var pressed = new Set();
  var qw = new Set();

  document.onkeydown = function(e) {
    pressed.add(e.keyCode);

    if (args.indexOf(e.keyCode) != -1) qw.add(e.keyCode);

    if (pressed.size === args.length && qw.size === args.length) {
      func();
      pressed.clear();
      qw.clear();
    };
  };

  document.onkeyup = function(e) {
    pressed.delete(e.keyCode);
    qw.delete(e.keyCode);
  };
};

function saveBlob(soundBlob, trackName) {
	var fd = new FormData();
	fd.append('fname', trackName + '.wav');
	fd.append('data', soundBlob);
	$.ajax({
	    type: 'POST',
	    url: 'flute',
	    data: fd,
	    processData: false,
	    contentType: false
	}).done(function(data) {
	       console.log(data);
	});
}
