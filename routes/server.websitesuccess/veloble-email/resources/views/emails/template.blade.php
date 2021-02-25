<html>

    <head>
        <style type="text/css">
            body {
                margin: 30px;
            }
            body,
            table {
                font: 12px/18px "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
            }
            table {
                border-right: 1px solid #eee;
                border-bottom: 1px solid #eee;
            }
            table td {
                border-top: 1px solid #eee;
                border-left: 1px solid #eee;
            }
            table span {
                color: #888;
            }
            .email-footer {
                background-color: #BAD709;
                padding: 30px;
            }
            .email-footer img {
                padding: 10px;
            }
            .email-wrapper {
                padding: 30px;
            }
        </style>
    </head>

    <body>
        <div style="margin-top:20px;background:#057eb4;padding: 30px 0 60px 0; min-height: 100%">
            <div class="email" style="margin:0 auto; max-width:580px;background: white; box-shadow: 3px 3px 0 #d2d2d2;">
                <p style="text-align: right"> <img src="https://www.veloble.com/images/veloble-logo-blue.png" style="width: 120px; padding: 10px;"> </p>
                <div class="email-wrapper"> @yield('content') </div>
                <div class="email-footer ">
                    <a href="https://www.instagram.com/veloble/ "><img src="https://www.veloble.com/images/build/instagram.png" style="width:35px; " alt=" " title=" "></a> 
                    <a href="https://twitter.com/veloble "><img src="https://www.veloble.com/images/build/twitter.png" style="width:35px; " alt=" " title=" "></a> 
                    <a href="https://www.facebook.com/veloble "><img src="https://www.veloble.com/images/build/facebook.png" style="width:35px; " alt=" " title=" "></a> 
                </div>
            </div>
        </div>
    </body>

</html>
