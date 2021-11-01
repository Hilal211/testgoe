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
                <td colspan="2" style="padding:10px;color:#666666"><b>Hello,</b></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">
                    Thank you for registering with Goecolo as a Store Owner! Please click <a href="{{route('verify.user', ['locale'=>App::getLocale(),'id' => \Crypt::encrypt($user->id),'email'=>$user->email])}}">here</a> to verify your account and then you're good to go!<br/><br/>
                    As a part of next step, please login and fill-in Account Details section in your profile. Once you fill-in account details, we will verify your account details and set it up and will let you know when you're all set to receive orders.
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">Thanks,<br/>-Team Goecolo</td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;font-size: 12px;;color:#BDBDBD; background: #3C464F">
                    You're receiving this email because you signed up for Goecolo'<br/>
                    Please do not reply to this email. Emails sent to this address will not be answered.<br/><br/>
                   Copyright © 2016 - {{Carbon::now()->format('Y')}} Goecolo. <br/>All rights reserved.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>