<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="moduleSeperatorLine">
    <tbody><tr>
        <td valign="top" align="center" bgcolor="#ffffff">
            <table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="eraseForMobile2">
                <tbody><tr>
                    <td valign="middle" height="10" align="center" bgcolor="#ffffff" style="font-size:0; line-height:0;">&nbsp;</td>
                </tr>
                </tbody></table>
            <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="table600">
                <tbody><tr>
                    <td valign="top" align="center" height="20" bgcolor="#ffffff" style="font-size:0; line-height:0;">&nbsp;</td>
                </tr>
                @if(isset($header) && strlen($header) > 0)
                <tr>
                    <!--======================== HEADER  SECTION =========================-->
                    <td valign="middle" align="center" bgcolor="#ffffff" class="introTextHeaderTD">{!! $header !!}</td>
                    <!--========================== End of the section =======================-->
                </tr>
                <tr>
                    <td valign="top" align="center" height="15" bgcolor="#ffffff" style="font-size:0; line-height:0;">&nbsp;</td>
                </tr>
                @endif
                <tr>
                    <!--============ REGULAR INFO (TEXT) HERE ==========-->
                    <td valign="middle" align="center" bgcolor="#ffffff" height="10" class="introTextTD">{!! $body !!}</td>
                    <!--================ End of the section ============-->
                </tr>
                <tr>
                    <td valign="top" align="center" height="20" bgcolor="#ffffff" style="font-size:0; line-height:0;">&nbsp;</td>
                </tr>
                </tbody></table>
            <table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" class="eraseForMobile2">
                <tbody><tr>
                    <td valign="middle" height="10" align="center" bgcolor="#ffffff" style="font-size:0; line-height:0;">&nbsp;</td>
                </tr>
                </tbody></table>
        </td>
    </tr>
    </tbody></table>