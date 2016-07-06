
<table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
    <tr>
        <!--=========== JUST ENTER YOUR INFO HERE =========-->
        <td valign="middle" align="center" bgcolor="#f4f4f4" height="10" class="sectionRegularInfoTextTD" style="border-collapse: collapse;color: #6e777e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 13px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 165%;letter-spacing: 0;">
            <br/>
            <div>Tournament Details:</div>
            <!--================== BEGIN SHOWING GUARDIAN/PLAYER INFO =================-->
            <table align="left" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0">
                <tbody>
                <tr>
                    <td style="padding-left: 1em; padding-top: .5em">
                        {!! EmailTemplate::link(url('tournaments/'.$tournament->slug), '<strong>'. $tournament->name .'</strong>') !!}<br/>
                        {{ $tournament->dateSpan() }}
                    </td>
                </tr>
                </tr>
                </tbody>
            </table>

        </td>
        <!--================ End of the section ============-->
    </tr>
</table>