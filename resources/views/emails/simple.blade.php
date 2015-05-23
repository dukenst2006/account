@extends('...layouts.email')

@section('subject', $message->getSubject())

@section('content')

    <!--================================================================================================================================ M O D U L E ==-->
    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4" class="moduleSeperatorLine" style="border-top-style: solid;border-top-color: #e5e5e5;border-top-width: 1px;">
        <tr valign="top">
            <td valign="top" align="center" bgcolor="#f4f4f4" style="border-collapse: collapse;">
                <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4" class="table600">
                    <tr>
                        <td valign="middle" height="30" align="center" bgcolor="#f4f4f4" style="font-size: 0;line-height: 0;border-collapse: collapse;">&nbsp;</td>
                    </tr>
                </table>
                <!--==== BULLETPROOF PADDING SECTION WITH GIVEN BG COLOR ====-->
                <table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
                    <tr>
                        <td valign="top" align="center" height="20" bgcolor="#f4f4f4" style="font-size: 0;line-height: 0;border-collapse: collapse;">&nbsp;</td>
                    </tr>
                </table>
                <!--================== End of the section ================-->
                <!--================== HEADER SECTION =================-->
                <table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
                    <tr>
                        <!--================= HEADER SECTION =============-->
                        <td valign="middle" align="center" height="10" bgcolor="#f4f4f4" class="sectionsHeaderTD" style="border-collapse: collapse;color: #43494e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 20px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 140%;letter-spacing: 0;">
                            @yield('title')
                        </td>
                        <!--================= End of the section =============-->
                    </tr>
                </table>
                <!--================== End of the section ================-->
                <!--==== BULLETPROOF PADDING SECTION WITH GIVEN BG COLOR ====-->
                <table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
                    <tr>
                        <td valign="top" align="center" height="15" bgcolor="#f4f4f4" style="font-size: 0;line-height: 0;border-collapse: collapse;border-bottom-style: solid;border-bottom-color: #e5e5e5;border-bottom-width: 1px;" class="headerAndTextSeperatorLine">&nbsp;</td>
                    </tr>
                </table>
                <table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
                    <tr>
                        <td valign="top" align="center" height="15" bgcolor="#f4f4f4" style="font-size: 0;line-height: 0;border-collapse: collapse;">&nbsp;</td>
                    </tr>
                </table>
                <!--================== End of the section ================-->
                <!-- ================== TEXT SECTION ==================-->
                <table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
                    <tr>
                        <!--=========== JUST ENTER YOUR INFO HERE =========-->
                        <td valign="middle" align="center" bgcolor="#f4f4f4" height="10" class="sectionRegularInfoTextTD" style="border-collapse: collapse;color: #6e777e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 13px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 165%;letter-spacing: 0;">
                            @yield('body')
                        </td>
                        <!--================ End of the section ============-->
                    </tr>
                </table>
                <!--================== End of the section ================-->
                <table width="600" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4" class="table600">
                    <tr>
                        <td valign="middle" height="30" align="center" bgcolor="#f4f4f4" style="font-size: 0;line-height: 0;border-collapse: collapse;">&nbsp;</td>
                    </tr>
                </table>
                <table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4" class="eraseForMobile2">
                    <tr>
                        <td valign="middle" height="10" align="center" bgcolor="#f4f4f4" style="font-size: 0;line-height: 0;border-collapse: collapse;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--========================================================================================================================= END OF THE MODULE ==-->


@endsection