<html>
<body>
    <div style="width:600px; border:15px #88c000 solid;margin:0 auto;font-family: sans-serif; color: #000AAA; display: table;">
        <table border="0" cellspacing="0" style="font-size: 14px;float: left;width: 100%;">
            <tr style="background: #EEEEEE;">
                <td align="center" colspan="2" style="padding:10px">
                    {{-- {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'logo-center.png',"") }} --}}
                    <img src="http://Goecolo.com/beta/public/assets/frontend/img/logo-center.png">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666"><b>{{trans('keywords.Hello, ')}}</b></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">
                    {{trans('keywords.Congratulations..!')}}<br/><br/>

                    {{trans('keywords.Welcome to Goecolo, the site that helps you compare prices, save money, buy locally, and protect the environment.')}}<br/><br/>
                    {{trans('keywords.To complete your registration with us please confirm your email by clicking on this ')}}<a href="">link</a>{{trans('keywords. and we’ll get you going.')}}<br/><br/>

                    {{trans('keywords.Happy Shopping!')}}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">{{trans('keywords.Thanks,')}}<br/>{{trans('keywords.Goecolo team, your tool to save.')}}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;font-size: 12px;;color:#BDBDBD; background: #3C464F">
                    {{trans('keywords.You\'re receiving this email because you signed up for Goecolo')}}<br/>
                    {{trans('keywords.Please do not reply to this email. Emails sent to this address will not be answered.')}}<br/><br/>
                    {{trans('keywords.Copyright &COPY; 2016 Goecolo. ')}}<br/>{{trans('keywords.All rights reserved.')}}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>