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
                <td colspan="2" style="padding:10px;color:#666666"><b>Bonjour,</b></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">
                    Un nouveau compte de magasin a été créé sur Goecolo. Voici l'info, c'est votre travail maintenant pour vérifier l'info.
                    <br><br>
                    <table border='1' style="border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="padding: 5px;"><b>Nom du magasin</b></td>
                                <td style="padding: 5px;">{{$store->storename}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;"><b>Nom de la personne-ressource du magasin</b></td>
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
                                <td style="padding: 5px;">Adresse du magasin</td>
                                <td style="padding: 5px;">{!! $storeAddress !!}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">Type de magasin</td>
                                <td style="padding: 5px;">{{$store->storetype}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px;">Offrent-ils une livraison à domicile?</td>
                                <td style="padding: 5px;">{{($store->homedelievery=='1' ? 'Oui' : 'Non')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;color:#666666">Merci,<br/>-Équipe GoEcolo</td>
            </tr>
            <tr>
                <td colspan="2" style="padding:10px;font-size: 12px;;color:#BDBDBD; background: #3C464F">
                    Vous recevez ce courriel parce que vous vous êtes inscrit à Goecolo<br/>
                    S'il vous plait ne répondez pas à cet email. Les e-mails envoyés à cette adresse ne seront pas répondus.<br/><br/>
                    Droits d'auteur © 2016 - {{Carbon::now()->format('Y')}} Goecolo.<br/>Tous les droits sont réservés.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>