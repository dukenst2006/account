<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>@yield('subject')</title>
    <style type="text/css">

        .ReadMsgBody{width: 100%;}
        .ExternalClass{width: 100%;}
        body{-webkit-text-size-adjust:100%;  -ms-text-size-adjust:100%;  -webkit-font-smoothing:antialiased; margin:0 !important;   padding:0 !important;   width:100% !important; }



        @media only screen and (max-width: 599px)
        {
            body{min-width:100% !important;}

            table[class=table600LogoAndMenuContainer]	{width:420px !important;}
            table[class=table600Logo]  					{width:420px !important; border-bottom-style:solid !important; border-bottom-color:#e5e5e5 !important; border-bottom-width:1px !important;}
            table[class=table600Logo] img 				{width:150px !important; height:100px !important; margin:0 auto 0 auto !important;}
            table[class=table600Menu]					{width:420px !important;}
            table[class=table600Menu] td					{height:20px !important;}
            table[class=table600Menu] .menuTD			{text-align:center !important; }

            table[class=table600] 						{width:420px !important;}
            table[class=table600AnnouncementText] 		{width:420px !important;}
            table[class=tableTextDateSection]			{width:420px !important;}
            td[class=logoMargin]							{height:8px !important;}
            td[class=logoMargin2]						{height:6px !important;}

            table[class=image600] img 					{width:420px !important; height:auto !important;}

            table[class=table280]						{width:420px !important;}
            td[class=table280Button] img					{width:230px !important; height:56px !important; margin:0 auto 0 auto !important;}
            td[class=table280Squareimage] img			{width:280px !important; height:auto !important; margin:30px auto 0 auto !important;}
            td[class=table280Rectangleimage] img		{width:280px !important; height:auto !important; margin:0 auto 0 auto !important;}
            td[class=table280Rectangleimage2] img		{width:280px !important; height:auto !important; margin:35px auto 0 auto !important;}
            td[class=table280Rectangleimage3] img		{width:280px !important; height:auto !important; margin:0 auto 15px auto !important;}

            table[class=table280Withicon] img			{width:45px !important; height:45px !important;}
            table[class=table280Withicon]				{width:420px !important;}
            table[class=table280Withicon] .sectionsHeaderTD{width:355px !important; text-align:left !important; font-size:20px !important;}

            table[class=table280Withicon2] img			{width:45px !important; height:45px !important;}
            table[class=table280Withicon2]				{width:420px !important; margin:35px auto 0 auto !important;}
            table[class=table280Withicon2] .sectionsHeaderTD{width:355px !important; text-align:left !important; font-size:20px !important;}

            td[class=socialicongr] img					{width:42px !important; height:42px !important;}

            td[class=announcementTextTD]				{text-align:center !important; font-weight:bold !important; font-size:17px !important;}
            td[class=introTextTD]						{text-align:center !important; font-size:13px !important;}
            td[class=introTextHeaderTD]					{text-align:center !important; font-size:20px !important;}
            td[class=sectionsSeperatorTextTD]			{text-align:center !important; font-weight:bold !important;}
            td[class=date]								{text-align:center !important;}

            td[class=sectionsHeaderTD] 					{font-size:20px !important; text-align:center !important;}
            td[class=sectionRegularInfoTextTD] 			{font-size:13px !important; text-align:left !important;}

            td[class=finalWords] 							{font-size:18px !important; text-align:center !important; }

            table[class=eraseForMobile] 					{width:0 !important; display:none !important;}
            table[class=eraseForMobile2] 				{height:0 !important; width:0 !important; display:none !important;}
        }



        @media only screen and (max-width: 479px)
        {
            body{min-width:100% !important;}

            table[class=table600LogoAndMenuContainer]	{width:280px !important;}
            table[class=table600Logo]  					{width:280px !important; border-bottom-style:solid !important; border-bottom-color:#e5e5e5 !important; border-bottom-width:1px !important;}
            table[class=table600Logo] img 				{width:150px !important; height:100px !important; margin:0 auto 0 auto !important;}
            table[class=table600Menu]					{width:280px !important;}
            table[class=table600Menu] td					{height:20px !important;}
            table[class=table600Menu] .menuTD			{text-align:center !important;}

            table[class=table600] 						{width:280px !important;}
            table[class=table600AnnouncementText] 		{width:280px !important;}
            table[class=tableTextDateSection] 			{width:280px !important;}
            td[class=logoMargin]							{height:8px !important;}
            td[class=logoMargin2]						{height:6px !important;}

            table[class=image600] img 					{width:280px !important; height:auto !important;}

            table[class=table280]						{width:280px !important;}
            td[class=table280Button] img					{width:230px !important; height:56px !important; margin:0 auto 0 auto !important;}
            td[class=table280Squareimage] img			{width:280px !important; height:auto !important; margin:30px auto 0 auto !important;}
            td[class=table280Rectangleimage] img		{width:280px !important; height:auto !important; margin:0 auto 0 auto !important;}
            td[class=table280Rectangleimage2] img		{width:280px !important; height:auto !important; margin:35px auto 0 auto !important;}
            td[class=table280Rectangleimage3] img		{width:280px !important; height:auto !important; margin:0 auto 15px auto !important;}

            table[class=table280Withicon] img			{width:45px !important; height:45px !important;}
            table[class=table280Withicon]				{width:280px !important;}
            table[class=table280Withicon] .sectionsHeaderTD{width:215px !important; text-align:left !important; font-size:18px !important;}

            table[class=table280Withicon2] img			{width:45px !important; height:45px !important;}
            table[class=table280Withicon2]				{width:280px !important; margin:35px auto 0 auto !important;}
            table[class=table280Withicon2] .sectionsHeaderTD{width:215px !important; text-align:left !important; font-size:18px !important;}

            td[class=socialicongr] img					{width:42px !important; height:42px !important;}

            td[class=announcementTextTD]				{text-align:center !important; font-weight:bold !important; font-size:17px !important;}
            td[class=introTextTD]						{text-align:center !important; font-size:14px !important;}
            td[class=introTextHeaderTD]					{text-align:center !important; font-size:18px !important;}
            td[class=sectionsSeperatorTextTD]			{text-align:center !important; font-weight:bold !important;}
            td[class=date]								{text-align:center !important;}

            td[class=sectionsHeaderTD] 					{font-size:18px !important; text-align:center !important; }
            td[class=sectionRegularInfoTextTD] 			{font-size:14px !important;}

            td[class=finalWords] 							{font-size:18px !important; text-align:center !important; }

            table[class=eraseForMobile] 					{width:0; display:none !important;}
            table[class=eraseForMobile2] 				{height:0 !important; width:0 !important; display:none !important;}
        }



    </style>
</head>
<body style="background-color: #f4f4f4;margin: 0;padding: 0;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;-webkit-font-smoothing: antialiased;width: 100%;">
<center>
    <!--=========================================================================================================================== HEADER SECTION ==-->
    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
        <tr bgcolor="#ffffff">
            <td valign="top" align="center" bgcolor="#ffffff" style="border-collapse: collapse;">
                <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600LogoAndMenuContainer">
                    <tr bgcolor="#ffffff">
                        <td valign="top" bgcolor="#ffffff" style="border-collapse: collapse;">
                            <table width="200" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600Logo">
                                <tr bgcolor="#ffffff">
                                    <!--============================================================ L O G O ==-->
                                    <!--=== IMPORTANT INFO = ABOUT THE USAGE OF THE RETINA READY LOGO IMAGE ====-->
                                    <!--=== The trick is to create the image at TWICE the size you actually plan on displaying them==-->
                                    <!--=== And then, shrink it down for the Retina Displays ====-->
                                    <!--=== AND I ALREADY DID IT FOR YOU ===-->
                                    <!--===  YOUR LOGO'S WIDTH MUST BE 300 PX, AND ITS HEIGHT MUST BE 200 px ==-->
                                    <!--=== PLEASE USE THE  "logo.PSD" File to customize your LOGO, and stick with the default size of the "logo.PSD" File==-->
                                    <!--=== Add your logoGraphic to "logo.PSD",  and center your logo vertically as I did by default (this ensures to have some space at top and bottom as a padding) ===-->
                                    <!--=== Then, save it as a JPG, and you're done ==-->
                                    <td valign="middle" align="center" bgcolor="#ffffff" style="border-collapse: collapse;color: #bbbbbb;font-family: sans-serif;font-size: 10px;padding: 0;margin: 0;;padding:1em">
                                        <a href="{{ url('/') }}" target="_blank" class="buttonsAndImagesLink" style="color: #bbbbbb;text-decoration: none;outline: none;">
                                            <img src="{{ url('img/logo-blue.png') }}" style="display:block;" alt="IMAGE HERE" border="0" align="top" hspace="0" vspace="0" width="174" height="56">
                                        </a>
                                    </td>
                                    <!--===================================================== End of the section ==-->
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--==================================================================================================================== END OF THE HEADER SECTION ==-->

    @yield('content')

</center>
</body>
</html>
