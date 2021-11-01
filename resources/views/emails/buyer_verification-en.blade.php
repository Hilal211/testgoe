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
                <td colspan="2" style="padding:10px;color:#666666"><b>Hello</b></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">
                    Congratulations..!<br/><br/>

                    Welcome to Goecolo, the site that helps you compare prices, save money, buy locally, and protect the environment. <br/><br/>
                    To complete your registration with us please confirm your email by clicking on this <a href="{{route('verify.user', ['locale'=>App::getLocale(),'id' => \Crypt::encrypt($user->id),'email'=>$user->email])}}">link</a> and we’ll get you going.<br/><br/>

                    Happy Shopping!
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">Thanks,<br/> Goecolo team, your tool to save.</td>
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