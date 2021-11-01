<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'le :attribute Doit être acceptée.',
    'active_url'           => 'le :attribute N\'est pas une URL valide.',
    'after'                => 'le :attribute Doit être une date après :date.',
    'alpha'                => 'le :attribute Ne peut contenir que des lettres.',
    'alpha_dash'           => 'le :attribute Ne peut contenir que des lettres, des chiffres et des tirets.',
    'alpha_num'            => 'le :attribute Ne peut contenir que des lettres et des chiffres.',
    'array'                => 'le :attribute Doit être un tableau.',
    'before'               => 'le :attribute Doit être une date avant :date.',
    'between'              => [
        'numeric' => 'le :attribute Doit être entre :min and :max.',
        'file'    => 'le :attribute Doit être entre :min and :max kilobytes.',
        'string'  => 'le :attribute Doit être entre :min and :max personnages.',
        'array'   => 'le :attribute Doit avoir entre :min and :max articles.',
    ],
    'boolean'              => 'le :attribute Champ doit être vrai ou faux.',
    'confirmed'            => 'le :attribute La confirmation ne correspond pas.',
    'date'                 => 'le :attribute N\'est pas une date valide.',
    'date_format'          => 'le :attribute Ne correspond pas au format :format.',
    'different'            => 'le :attribute et :other Doit être différente.',
    'digits'               => 'le :attribute doit être :digits chiffres.',
    'digits_between'       => 'le :attribute Doit être entre :min et :max chiffres.',
    'distinct'             => 'le :attribute Champ a une valeur dupliquée.',
    'email'                => 'le :attribute Doit être une adresse e-mail valide.',
    'exists'               => 'Les :attribute est invalide.',
    'filled'               => 'le :attribute Champ requis.',
    'image'                => 'le :attribute Doit être une image.',
    'in'                   => 'Les :attribute est invalide.',
    'in_array'             => 'le :attribute Champ n\'existe pas dans :other.',
    'integer'              => 'le :attribute Doit être un entier.',
    'ip'                   => 'le :attribute Doit être une adresse IP valide.',
    'json'                 => 'le :attribute Doit être une chaîne JSON valide.',
    'max'                  => [
        'numeric' => 'le :attribute Ne peut être supérieur à :max.',
        'file'    => 'le :attribute Ne peut être supérieur à :max kilobytes.',
        'string'  => 'le :attribute Ne peut être supérieur à :max personnages.',
        'array'   => 'le :attribute Peut ne pas avoir plus de :max articles.',
    ],
    'mimes'                => 'le :attribute Doit être un fichier de type: :values.',
    'min'                  => [
        'numeric' => 'le :attribute doit être au moins :min.',
        'file'    => 'le :attribute doit être au moins :min kilobytes.',
        'string'  => 'le :attribute doit être au moins :min personnages.',
        'array'   => 'le :attribute Doit avoir au moins :min articles.',
    ],
    'not_in'               => 'Les :attribute est invalide.',
    'numeric'              => 'le :attribute doit être un nombre.',
    'present'              => 'le :attribute Doit être présent.',
    'regex'                => 'le :attribute Format n\'est pas valide.',
    'required'             => 'le :attribute Champ requis.',
    'required_if'          => 'le :attribute Champ est requis lorsque :other est :value.',
    'required_unless'      => 'le :attribute Champ est obligatoire sauf si :other est dans :values.',
    'required_with'        => 'le :attribute Champ est requis lorsque :values est présent.',
    'required_with_all'    => 'le :attribute Champ est requis lorsque :values est présent.',
    'required_without'     => 'le :attribute Champ est requis lorsque :values N\'est pas présent.',
    'required_without_all' => 'le :attribute Est obligatoire lorsque aucun :values sont présents.',
    'same'                 => 'le :attribute et :other doit correspondre.',
    'size'                 => [
        'numeric' => 'le :attribute doit être :size.',
        'file'    => 'le :attribute doit être :size kilobytes.',
        'string'  => 'le :attribute doit être :size personnages.',
        'array'   => 'le :attribute doit contenir :size articles.',
    ],
    'string'               => 'le :attribute Doit être une chaîne.',
    'timezone'             => 'le :attribute Doit être une zone valide.',
    'unique'               => 'le :attribute a déjà été pris.',
    'url'                  => 'le :attribute Le format n\'est pas valide.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [

        'email'=>'email',
        'password'=>'mot de passe',
        'state'=>'etat',
        'city'=>'ville',
        'captch'=>'captcha',
        'contact'=>'contact',
        'store_name'=>'nom de magasin',
        'address_1'=>'adresse 1',
        'zip'=>'postol',
        'storetype'=>'type de magasin',
        'home_delivery'=>'livraison à domicile',
        'gst'=>'gst',
        'hst'=>'hst',
        'legal_entity_name'=>'Nom de l\'entité juridique',
        'year'=>'an',
        'name'=>'prénom',
        'message'=>'message',

    ],

];
