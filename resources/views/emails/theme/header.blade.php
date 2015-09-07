<table width="600" align="center" cellpadding="0" cellspacing="0" bgcolor="#f4f4f4" border="0" class="table600">
    <tr>
        <!--================= HEADER SECTION =============-->
        <td valign="middle" align="center" height="10" bgcolor="#f4f4f4" class="sectionsHeaderTD" style="border-collapse: collapse;color: #43494e;font-family: Arial, Tahoma, Verdana, sans-serif;font-size: 20px;font-weight: lighter;padding: 0;margin: 0;text-align: left;line-height: 140%;letter-spacing: 0;">{{ $header }}</td>
        <!--================= End of the section =============-->
    </tr>
</table>

{{-- Use this parameter to show the header without it's bottom border/spacing --}}
@if('hideBottomBorder' !== true)
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
@endif