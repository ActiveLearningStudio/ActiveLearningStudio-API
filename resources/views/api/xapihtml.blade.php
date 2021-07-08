<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{$title}}</title>
    <style type="text/css">
    	body {
    		margin:0;
    		padding:0;
    	}
    	iframe {
    		width:99vw;
    		height:99vh;
    		border:none;
    	}
    </style>
</head>
<body>
    <script type="text/javascript">
        let frame = document.createElement("iframe");
        let params = window.location.search;
        frame.setAttribute('src', '{{$url}}'+params);
        document.body.appendChild(frame);
    </script>
</body>
</html>
