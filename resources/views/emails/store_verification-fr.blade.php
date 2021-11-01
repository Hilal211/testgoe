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
                    Merci de vous être inscrit à Goecolo en tant que propriétaire de magasin! Cliquez s'il vous plait <a href="{{route('verify.user', ['locale'=>App::getLocale(),'id' => \Crypt::encrypt($user->id),'email'=>$user->email])}}">ici</a> Pour vérifier votre compte et puis vous êtes bon à démarrer<br/><br/>
                    
                    Pour l'étape suivante, veuillez vous connecter et remplir la section Détails du compte de votre profil. Une fois que vous avez rempli vos détails, nous vérifierons les détails de votre compte et le configurerons et vous indiquerons quand vous êtes prêt à recevoir des commandes.
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