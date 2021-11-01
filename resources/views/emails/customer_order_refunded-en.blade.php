<html>
<body>
    <div style="width:600px; border:15px #88c000 solid;margin:0 auto;font-family: sans-serif; color: #000AAA; display: table;">
        <table border="0" cellspacing="0" style="font-size: 14px;float: left;width: 100%;">
            <tr style="background: #EEEEEE;">
                <td align="center" colspan="2" style="padding:10px">
                    <img src="https://www.goecolo.com/public/assets/frontend/img/logo-center-{{App::getLocale()}}.png">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666"><b>Hello {{$user->username}}</b></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">
                    We have refunded your order {{$order->order_number}}, please allow 5 to 7 business days for the amount to appear on your account.
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">Thanks,<br/>-Team Goecolo</td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;font-size: 12px;;color:#BDBDBD; background: #3C464F">
                    You're receiving this email because you signed up for Goecolo<br/>
                    Please do not reply to this email. Emails sent to this address will not be answered.<br/><br/>
                    Copyright © 2016 - {{Carbon::now()->format('Y')}} Goecolo. <br/>All rights reserved.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>