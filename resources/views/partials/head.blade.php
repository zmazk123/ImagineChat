<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

 <!--This if of course not the best way-->
<script>
    var eventSource = new EventSource('/sse');
    
    eventSource.onmessage = function(event) {
        console.log('Message received: ' + event.data);

        var payload = JSON.parse(event.data);
        if(payload.type === "message") {
            //Livewire.dispatch('test');
            var div = document.getElementById("messages");
            div.innerHTML += '<div><strong class="text-blue-400">' + payload.name + '</strong>: <span class="text-gray-200">' + payload.message + '</span></div>';
        }
    };
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
