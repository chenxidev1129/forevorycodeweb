<script src="{{ url('assets/js/recorder-lib/WebAudioRecorder.min.js') }}"></script>
<script>
//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var recorder; 						//WebAudioRecorder object
var input; 							//MediaStreamAudioSourceNode  we'll be recording
var encodingType; 					//holds selected encoding for resulting audio (file)
var encodeAfterRecord = true;       // when to encode

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext; //new audio context to help us record

//var encodingTypeSelect = document.getElementById("encodingTypeSelect");
var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");

var minutesLabel = document.getElementById("minutesTimer");
var secondsLabel = document.getElementById("secondsTimer");
var totalSeconds = 0;
var audioTimer;

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);

function startRecording() {

	
	/*
		Simple constraints object, for more advanced features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/
    
    var constraints = { audio: true, video:false }

    /*
    	We're using the standard promise based getUserMedia() 
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		//__log("getUserMedia() success, stream created, initializing WebAudioRecorder...");

		/* start timer */
		$('#minutesTimer, #secondsTimer').html('00');
        $('#voiceRecord #recordingsList').html('');

		audioTimer = setInterval(audioClock, 1000);
		totalSeconds = 0;
    
		$('.startRecording').addClass('commonSec');
        $('.startRecording').removeAttr('style');
        $('.speakNow').css('display', 'block');
		
		
		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();

		//update the format 
		//document.getElementById("formats").innerHTML="Format: 2 channel "+encodingTypeSelect.options[encodingTypeSelect.selectedIndex].value+" @ "+audioContext.sampleRate/1000+"kHz"

		//assign to gumStream for later use
		gumStream = stream;
		
		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);
		
		//stop the input from playing back through the speakers
		//input.connect(audioContext.destination)

		//get the encoding 
		//encodingType = encodingTypeSelect.options[encodingTypeSelect.selectedIndex].value;
		encodingType = "mp3";
		
		//disable the encoding selector
		//encodingTypeSelect.disabled = true;

		recorder = new WebAudioRecorder(input, {
			workerDir: "{{ url('assets/js/recorder-lib')}}/", // must end with slash
			encoding: encodingType,
			numChannels:2, //2 is the default, mp3 encoding supports only 2
			onEncoderLoading: function(recorder, encoding) {
				// show "loading encoder..." display
				//__log("Loading "+encoding+" encoder...");
			},
			onEncoderLoaded: function(recorder, encoding) {
				// hide "loading encoder..." display
				//__log(encoding+" encoder loaded");
			}
		});

		recorder.onComplete = function(recorder, blob) { 
			//__log("Encoding complete");
			createDownloadLink(blob,recorder.encoding);
			//encodingTypeSelect.disabled = false;

			clearInterval(audioTimer);
		}

		recorder.setOptions({
			timeLimit:300,
			encodeAfterRecord: encodeAfterRecord,
			ogg: {quality: 0.5},
			mp3: {bitRate: 160}
	    });

		//start the recording process
		recorder.startRecording();
        
		/* Stope recording after 5 minute */
		recorder.onTimeout = function(recorder) {
			clearInterval(audioTimer);
			stopButton.click();
		}

		

	}).catch(function(err) {
	  	//enable the record button if getUSerMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
		_toast.error('Please connect your Mic');

	});

	//disable the record button
    recordButton.disabled = true;
    stopButton.disabled = false;
}

function stopRecording() {
	clearInterval(audioTimer);
	/* Stop microphone access */
	gumStream.getAudioTracks()[0].stop();

	/* Disable the stop button */
	stopButton.disabled = true;
	recordButton.disabled = false;
	
	//tell the recorder to finish the recording (stop recording + encode the recorded audio)
	recorder.finishRecording();

}

function createDownloadLink(blob,encoding) {
	
	var url = URL.createObjectURL(blob);
	var au = document.createElement('audio');
	var li = document.createElement('li');
	var link = document.createElement('a');

	/* Add controls to the <audio> element */
	au.controls = true;
	au.controlsList="nodownload noplaybackrate";
	au.src = url;

	/* link the a element to the blob */
	link.href = url;
	link.download = new Date().toISOString() + '.'+encoding;
	link.innerHTML = link.download;

	var filename = new Date().toISOString();
	/*filename to send to server without extension upload link */ 
	var upload = document.getElementById("uploadRecordAudio");
	upload.addEventListener("click", function(event) {
		/* upload.disabled = true; */
		$('#uploadRecordAudio').addClass('disabled');

		var xhr = new XMLHttpRequest();
		xhr.onload = function(e) {
			
			var data = jQuery.parseJSON(this.response);
			
			if (this.readyState === 4) {
				if(data.status){
				    location.reload('profile');
				}else{
					addMixpanelEvent('Guest book Signed');
					loadProfileMediaAudio();
					loadProfileGuestBook();
					$('#voiceRecord').modal('hide');
					_toast.success('Audio saved successfully.');
				}
			}
		};
		var fd = new FormData();
		fd.append("audio", blob, filename);
		fd.append("_token","{{ csrf_token() }}");
		fd.append("profile_id", $(".profile_id").val());

		var audio_duration = 0.00;
		audio_duration = pad(parseInt(totalSeconds / 60)) +':'+ pad(totalSeconds % 60);
        
		fd.append('duration', audio_duration);
		xhr.open("POST", "{{url('upload-record-voice-note')}}", true);
		xhr.send(fd);
		
	})


	//add the new audio and a elements to the li element
	li.appendChild(au);
	li.appendChild(link);

	//add the li element to the ordered list
	//recordingsList.appendChild(li);
	recordingsList.appendChild(au);
	$('#uploadRecordAudio').removeClass('disabled');
}



//helper function
// function __log(e, data) {
// 	log.innerHTML += "\n" + e + " " + (data || '');
// }

/* Show audio timer  */
function audioClock() {
	++totalSeconds;
	secondsLabel.innerHTML = pad(totalSeconds % 60);
	minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
	
}

function pad(val) {
	var valString = val + "";
	if (valString.length < 2) {
		return "0" + valString;
	} else {
		return valString;
	}
}
</script>