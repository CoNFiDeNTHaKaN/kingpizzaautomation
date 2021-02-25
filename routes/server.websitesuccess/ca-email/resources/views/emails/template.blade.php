<html>

    <head>
        <style type="text/css">
            body {
                margin: 30px;
            }
            body,
            table {
                font: 12px/18px Helvetica, Arial, Verdana, sans-serif;
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
                color: #404040;
            }
            .email-footer {
                background-color: #36374c;
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
        <div style="margin-top:20px;background:#3e3f52;padding: 30px 0 60px 0; min-height: 100%">
            <div class="email" style="margin:0 auto; max-width:580px;background: white; box-shadow: 3px 3px 0 #d2d2d2;">
                <p style="text-align: right"> <img src="https://curiosityapproach.worldsecuresystems.com/images/build/the-curiosity-approach.svg" style="width: 160px; padding: 10px;"> </p>
                <div class="email-wrapper"> @yield('content') </div>
                <div class="email-footer ">
                    <a href="https://www.facebook.com/curiousityapproach/"><img src="https://curiosityapproach.worldsecuresystems.com/images/build/facebook-icon.png" style="height: 30px;" alt=" " title=" "></a>
                    <a href="https://www.instagram.com/curiosityapproach/"><img src="https://curiosityapproach.worldsecuresystems.com/images/build/instagram-icon.png" style="height: 30px;" alt=" " title=" "></a>
                    <a href="https://www.youtube.com/channel/UCw8Vq8Lf8Xsna-ZqYF2Pylg"><img src="https://curiosityapproach.worldsecuresystems.com/images/build/youtube-icon.png" style="height: 30px;" alt=" " title=" "></a>
                </div>
            </div>
        </div>
    </body>

</html>
