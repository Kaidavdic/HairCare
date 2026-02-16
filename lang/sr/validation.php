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

    'accepted'             => 'Polje :attribute mora biti prihvaćeno.',
    'active_url'           => 'Polje :attribute nije važeći URL.',
    'after'                => 'Polje :attribute mora biti datum nakon :date.',
    'after_or_equal'      => 'Polje :attribute mora biti datum nakon ili jednak :date.',
    'alpha'                => 'Polje :attribute može sadržati samo slova.',
    'alpha_dash'           => 'Polje :attribute može sadržati samo slova, brojeve, crtice i donje crte.',
    'alpha_num'            => 'Polje :attribute može sadržati samo slova i brojeve.',
    'array'                => 'Polje :attribute mora biti niz.',
    'before'               => 'Polje :attribute mora biti datum pre :date.',
    'before_or_equal'     => 'Polje :attribute mora biti datum pre ili jednak :date.',
    'between'              => [
        'numeric' => 'Polje :attribute mora biti između :min i :max.',
        'file'    => 'Fajl :attribute mora biti između :min i :max kilobajta.',
        'string'  => 'Polje :attribute mora biti između :min i :max karaktera.',
        'array'   => 'Polje :attribute mora imati između :min i :max stavki.',
    ],
    'boolean'              => 'Polje :attribute mora biti tačno ili netačno.',
    'confirmed'            => 'Potvrda polja :attribute se ne poklapa.',
    'date'                 => 'Polje :attribute nije važeći datum.',
    'date_equals'          => 'Polje :attribute mora biti datum jednak :date.',
    'date_format'          => 'Polje :attribute se ne poklapa sa formatom :format.',
    'different'            => 'Polja :attribute i :other moraju biti različita.',
    'digits'               => 'Polje :attribute mora imati :digits cifara.',
    'digits_between'       => 'Polje :attribute mora imati između :min i :max cifara.',
    'dimensions'           => 'Polje :attribute ima nevažeće dimenzije slike.',
    'distinct'             => 'Polje :attribute ima dupliranu vrednost.',
    'email'                => 'Polje :attribute mora biti važeća email adresa.',
    'ends_with'            => 'Polje :attribute se mora završiti sa jednim od sledećih: :values.',
    'exists'               => 'Izabrano polje :attribute nije važeće.',
    'file'                 => 'Polje :attribute mora biti fajl.',
    'filled'               => 'Polje :attribute mora imati vrednost.',
    'gt'                   => [
        'numeric' => 'Polje :attribute mora biti veće od :value.',
        'file'    => 'Fajl :attribute mora biti veći od :value kilobajta.',
        'string'  => 'Polje :attribute mora imati više od :value karaktera.',
        'array'   => 'Polje :attribute mora imati više od :value stavki.',
    ],
    'gte'                  => [
        'numeric' => 'Polje :attribute mora biti veće ili jednako :value.',
        'file'    => 'Fajl :attribute mora biti veći ili jednak :value kilobajta.',
        'string'  => 'Polje :attribute mora imati :value ili više karaktera.',
        'array'   => 'Polje :attribute mora imati :value ili više stavki.',
    ],
    'image'                => 'Polje :attribute mora biti slika.',
    'in'                   => 'Izabrano polje :attribute nije važeće.',
    'in_array'             => 'Polje :attribute ne postoji u :other.',
    'integer'              => 'Polje :attribute mora biti ceo broj.',
    'ip'                   => 'Polje :attribute mora biti važeća IP adresa.',
    'ipv4'                 => 'Polje :attribute mora biti važeća IPv4 adresa.',
    'ipv6'                 => 'Polje :attribute mora biti važeća IPv6 adresa.',
    'json'                 => 'Polje :attribute mora biti važeći JSON string.',
    'lt'                   => [
        'numeric' => 'Polje :attribute mora biti manje od :value.',
        'file'    => 'Fajl :attribute mora biti manji od :value kilobajta.',
        'string'  => 'Polje :attribute mora imati manje od :value karaktera.',
        'array'   => 'Polje :attribute mora imati manje od :value stavki.',
    ],
    'lte'                  => [
        'numeric' => 'Polje :attribute mora biti manje ili jednako :value.',
        'file'    => 'Fajl :attribute mora biti manje ili jednako :value kilobajta.',
        'string'  => 'Polje :attribute mora imati :value ili manje karaktera.',
        'array'   => 'Polje :attribute ne sme imati više od :value stavki.',
    ],
    'max'                  => [
        'numeric' => 'Polje :attribute ne sme biti veće od :max.',
        'file'    => 'Fajl :attribute ne sme biti veći od :max kilobajta.',
        'string'  => 'Polje :attribute ne sme imati više od :max karaktera.',
        'array'   => 'Polje :attribute ne sme imati više od :max stavki.',
    ],
    'mimes'                => 'Polje :attribute mora biti fajl tipa: :values.',
    'mimetypes'            => 'Polje :attribute mora biti fajl tipa: :values.',
    'min'                  => [
        'numeric' => 'Polje :attribute mora biti najmanje :min.',
        'file'    => 'Fajl :attribute mora biti najmanje :min kilobajta.',
        'string'  => 'Polje :attribute mora imati najmanje :min karaktera.',
        'array'   => 'Polje :attribute mora imati najmanje :min stavki.',
    ],
    'not_in'               => 'Izabrano polje :attribute nije važeće.',
    'not_regex'            => 'Format polja :attribute nije važeći.',
    'numeric'              => 'Polje :attribute mora biti broj.',
    'password'             => [
        'letters' => 'Polje :attribute mora sadržati bar jedno slovo.',
        'mixed' => 'Polje :attribute mora sadržati bar jedno veliko i jedno malo slovo.',
        'numbers' => 'Polje :attribute mora sadržati bar jedan broj.',
        'symbols' => 'Polje :attribute mora sadržati bar jedan simbol.',
        'uncompromised' => 'Data lozinka :attribute se pojavila u curenju podataka. Molimo izaberite drugu :attribute.',
    ],
    'present'              => 'Polje :attribute mora biti prisutno.',
    'regex'                => 'Format polja :attribute nije važeći.',
    'required'             => 'Polje :attribute je obavezno.',
    'required_if'          => 'Polje :attribute je obavezno kada je :other jednako :value.',
    'required_unless'      => 'Polje :attribute je obavezno osim ako je :other u :values.',
    'required_with'        => 'Polje :attribute je obavezno kada je :values prisutno.',
    'required_with_all'    => 'Polje :attribute je obavezno kada su :values prisutni.',
    'required_without'     => 'Polje :attribute je obavezno kada :values nije prisutno.',
    'required_without_all' => 'Polje :attribute je obavezno kada nijedno od :values nije prisutno.',
    'same'                 => 'Polja :attribute i :other moraju se poklapati.',
    'size'                 => [
        'numeric' => 'Polje :attribute mora biti :size.',
        'file'    => 'Fajl :attribute mora biti :size kilobajta.',
        'string'  => 'Polje :attribute mora imati :size karaktera.',
        'array'   => 'Polje :attribute mora sadržati :size stavki.',
    ],
    'starts_with'          => 'Polje :attribute mora početi sa jednim od sledećih: :values.',
    'string'               => 'Polje :attribute mora biti niz karaktera.',
    'timezone'             => 'Polje :attribute mora biti važeća vremenska zona.',
    'unique'               => 'Polje :attribute je već zauzeto.',
    'uploaded'             => 'Fajl je prevelik.',
    'url'                  => 'Format polja :attribute nije važeći.',
    'uuid'                 => 'Polje :attribute mora biti važeći UUID.',
    'password_history'     => 'Nova lozinka ne sme biti ista kao neka od poslednje :count korišćene lozinke.',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'ime',
        'username' => 'korisničko ime',
        'email' => 'email adresa',
        'password' => 'lozinka',
        'password_confirmation' => 'potvrda lozinke',
        'phone' => 'telefon',
        'role' => 'uloga',
        'interests' => 'interesovanja',
    ],

];
