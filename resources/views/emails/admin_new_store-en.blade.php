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
                    A new store account has been created on Goecolo. Here are the info, it’s your job now to verify the info.
                    <br><br>
                    <table border='1' style="border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="padding: 5px;"><b>Store Name</b></td>
                                <td style="padding: 5px;">{{$store->storename}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;"><b>Contact Person</b></td>
                                <td style="padding: 5px;">{{$user->username}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">Email</td>
                                <td style="padding: 5px;">{{$user->email}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">Contact</td>
                                <td style="padding: 5px;">{{$store->contactnumber}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">Store Address</td>
                                <td style="padding: 5px;">{!! $storeAddress !!}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">Type of Store</td>
                                <td style="padding: 5px;">{{$store->storetype}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">Do they provide home delivery?</td>
                                <td style="padding: 5px;">{{($store->homedelievery=='1' ? 'Yes' : 'No')}}</td>
                            </tr>
                        </tbody>
                    </table>
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