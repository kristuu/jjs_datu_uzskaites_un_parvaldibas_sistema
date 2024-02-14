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

    'accepted' => 'Laukam ":attribute" ir jābūt atzīmētam',
    'accepted_if' => 'Laukam ":attribute" ir jābūt atzīmētam, ja lauka :other vērtība ir :value',
    'active_url' => '":attribute" laukam jābūt derīgai saitei (URL)',
    'after' => 'Laukam ":attribute" jābūt datumam pēc :date',
    'after_or_equal' => 'Laukam ":attribute" jābūt datumam, kas ir vienāds ar vai nav senāks par :date',
    'alpha' => 'Laukam ":attribute" jāsastāv tikai no burtiem',
    'alpha_dash' => 'Lauks ":attribute" var saturēt tikai burtus, ciparus, defises (-) un apakšsvītras (_)',
    'alpha_num' => 'Lauks ":attribute" var saturēt tikai burtus un ciparus',
    'array' => 'Laukam ":attribute" ir jābūt masīvam',
    'ascii' => 'Lauks ":attribute" var saturēt tikai viena baita alfanumeriskās zīmes un simbolus',
    'before' => 'Laukam ":attribute" ir jābūt datumam, kas ir senāks par :date',
    'before_or_equal' => 'Laukam ":attribute" ir jābūt datumam, kas ir vienāds ar vai senāks par :date',
    'between' => [
        'array' => 'Laukam ":attribute" jābūt robežās no :min līdz :max vienībām',
        'file' => 'Lauka ":attribute" faila izmēram jābūt robežās no :min līdz :max kilobaitiem',
        'numeric' => 'Lauka ":attribute" vērtībai jābūt robežās no :min līdz :max',
        'string' => 'Lauka ":attribute" satura graumam jābūt robežās no :min līdz :max rakstzīmju',
    ],
    'boolean' => 'Lauka ":attribute" vērtībai jābūt patiesai vai nepatiesai',
    'can' => 'Lauks ":attribute" satur neatļautu vērtību',
    'confirmed' => 'Lauka ":attribute" apstiprinājums nesakrīt ar lauka ":attribute" vērtību',
    'current_password' => 'Ievadītā parole ir nepareiza',
    'date' => 'Lauka ":attribute" vērtībai ir jābūt derīgam datumam',
    'date_equals' => 'Lauka ":attribute" vērtībai ir jābūt datumam, kas ir vienāds ar :date',
    'date_format' => 'Lauka ":attribute" vērtībai ir jāatbilst formātam :format',
    'decimal' => 'Lauka ":attribute" vērtībai ir jābūt :decimal decimālajām vietām',
    'declined' => 'Laukam ":attribute" jābūt noraidītam',
    'declined_if' => 'Laukam ":attribute" jābūt noraidītam gadījumā, kad :other ir :value',
    'different' => 'Laukiem ":attribute" un :other jābūt atšķirīgiem',
    'digits' => 'Lauka ":attribute" vērtībai ir jābūt :digits cipariem',
    'digits_between' => 'Lauka ":attribute" vērtības skaitļa garumam jābūt robežās no :min līdz :max cipariem',
    'dimensions' => 'Lauka ":attribute" attēla dimensijas nav derīgas',
    'distinct' => 'Lauks ":attribute" satur dublikāta vērtību',
    'doesnt_end_with' => 'Lauka ":attribute" vērtība nedrīkst beigties ar vienu no sekojošajiem: :values',
    'doesnt_start_with' => 'Lauka ":attribute" vērtība nedrīkst sākties ar vienu no sekojošajiem: :values',
    'email' => 'Lauka ":attribute" vērtībai ir jābūt derīgai e-pasta adresei',
    'ends_with' => 'Lauka ":attribute" vērtībai jābeidzas ar vienu no sekojošajiem: :values',
    'enum' => 'Izvēlētā lauka ":attribute" vērtība ir nederīga',
    'exists' => 'Izvēlētā lauka ":attribute" vērtība jau eksistē',
    'extensions' => 'Laukam ":attribute" ir jābūt vienam no sekojošajiem paplašinājumiem: :values',
    'file' => 'Laukam ":attribute" jāsatur fails',
    'filled' => 'Laukam ":attribute" ir jāsatur vērtība',
    'gt' => [
        'array' => 'Laukam ":attribute" jāsatur vismaz :value vienību',
        'file' => 'Lauka ":attribute" pievienotā faila izmēram ir jābūt lielākam par :value kilobaitu',
        'numeric' => 'Lauka ":attribute" vērtībai ir jābūt lielākai par :value',
        'string' => 'Lauka ":attribute" vērtības garumam ir jābūt lielākam par :value simbolu',
    ],
    'gte' => [
        'array' => 'Laukam ":attribute" jāsatur vismaz :value vienību',
        'file' => 'Lauka ":attribute" pievienotā faila izmēram ir jābūt vienādam ar vai lielākam par :value kilobaitu',
        'numeric' => 'Lauka ":attribute" vērtībai ir jābūt vienādai ar vai lielākai par :value',
        'string' => 'Lauka ":attribute" vērtības garumam ir jābūt vienādam ar vai lielākam par :value rakstzīmju',
    ],
    'hex_color' => 'Laukam ":attribute" jāsatur derīgs hex krāsas kods',
    'image' => 'Laukam ":attribute" jāsatur foto',
    'in' => 'Izvēlētā lauka ":attribute" vērtība ir nederīga',
    'in_array' => 'Laukam ":attribute" ir jāeksistē iekš :other',
    'integer' => 'Laukam ":attribute" jāsatur vesels skaitlis',
    'ip' => 'Laukam ":attribute" jāsatur derīga IP adrese',
    'ipv4' => 'Laukam ":attribute" jāsatur derīga IPv4 adrese',
    'ipv6' => 'Laukam ":attribute" jāsatur derīga IPv6 adrese',
    'json' => 'Laukam ":attribute" jāsatur derīga JSON virkne',
    'lowercase' => 'Lauka ":attribute" vērtībai jāsatur tikai mazos burtus',
    'lt' => [
        'array' => 'Laukam ":attribute" jāsatur mazāk par :value vienību',
        'file' => 'Laukam ":attribute" pievienotā faila izmēram jābūt mazākam, nekā :value kilobaitu',
        'numeric' => 'Lauka ":attribute" vērtībai jābūt mazākai par :value',
        'string' => 'Lauka ":attribute" vērtības garumam jābūt mazākam par :value rakstzīmju',
    ],
    'lte' => [
        'array' => 'Laukam ":attribute" jāsatur :value vai mazāk vienību',
        'file' => 'Laukam ":attribute" pievienotā faila izmēram ir jābūt vienādam ar vai mazākam, nekā :value kilobaitu',
        'numeric' => 'Lauka ":attribute" vērtībai jābūt vienādai ar vai mazākai par :value',
        'string' => 'Lauka ":attribute" vērtības garumam jābūt vienādam ar vai mazākam par :value rakstzīmju',
    ],
    'mac_address' => 'Laukam ":attribute" jāsatur derīga MAC adrese',
    'max' => [
        'array' => 'Lauks ":attribute" nedrīkst saturēt vairāk par :max vienību',
        'file' => 'Laukam ":attribute" pievienotā faila izmērs nedrīkst pārsniegt :max kilobaitu',
        'numeric' => 'Lauka ":attribute" vērtība nedrīkst pārsniegt :max',
        'string' => 'Lauka ":attribute" vērtības garums nedrīkst pārsniegt :max rakstzīmju',
    ],
    'max_digits' => 'Lauks ":attribute" nedrīkst saturēt vairāk par :max ciparu',
    'mimes' => 'Laukam ":attribute" pievienotajam failam jābūt :values failu tipu ietvaros',
    'mimetypes' => 'Laukam ":attribute" pievienotajam failam jābūt :values failu tipu ietvaros',
    'min' => [
        'array' => 'Laukam ":attribute" jāsatur vismaz :min vienību',
        'file' => 'Laukam ":attribute" pievienotā faila izmēram ir jābūt vismaz :min kilobaitu',
        'numeric' => 'Lauka ":attribute" vērtībai jābūt vismaz :min',
        'string' => 'Lauka ":attribute" vērtības garumam jābūt vismaz :min',
    ],
    'min_digits' => 'Laukam ":attribute" jāsatur vismaz :min ciparu',
    'missing' => 'Lauks ":attribute" nedrīkst saturēt vērtību',
    'missing_if' => 'Lauks ":attribute" nedrīkst saturēt vērtību, kad :other ir :value',
    'missing_unless' => 'Lauks ":attribute" nedrīkst saturēt vērtību, ja :other nav :value',
    'missing_with' => 'Lauks ":attribute" nedrīkst saturēt vērtību gadījumā, ja eksistē :values',
    'missing_with_all' => 'Lauks ":attribute" nedrīkst saturēt vērtību gadījumā, ja eksistē :values',
    'multiple_of' => 'Laukam ":attribute" jābūt :value reizinājumam ar citu ciparu vai skaitli',
    'not_in' => 'Lauka ":attribute" izvēlētā vērtība nav derīga',
    'not_regex' => 'Lauka ":attribute" formāts nav derīgs',
    'numeric' => 'Lauka ":attribute" vērtībai ir jābūt skaitliskai',
    'password' => [
        'letters' => 'Laukam ":attribute" jāsatur vismaz viens burts',
        'mixed' => 'Laukam ":attribute" jāsatur gan mazie, gan lielie burti',
        'numbers' => 'Laukam ":attribute" jāsatur vismaz viens cipars',
        'symbols' => 'Laukam ":attribute" jāsatur vismaz viens simbols',
        'uncompromised' => 'Norādītā lauka ":attribute" vērtība ir sastopama datu noplūdēs. Lūdzu, izvēlieties citu, drošāku lauka ":attribute" vērtību',
    ],
    'present' => 'Laukam ":attribute" ir jābūt norādītam',
    'present_if' => 'Laukam ":attribute" ir jābūt norādītam, ja lauka :other vērtība ir :value',
    'present_unless' => 'Laukam ":attribute" ir jābūt norādītam, ja vien lauka :other vērtība nav :value',
    'present_with' => 'Laukam ":attribute" ir jābūt norādītam, ja :values ir norādītas',
    'present_with_all' => 'Laukam ":attribute" ir jābūt norādītam, ja :values ir norādītas',
    'prohibited' => 'Lauka ":attribute" aizpildīšana nav atļauta',
    'prohibited_if' => 'Lauka ":attribute" aizpildīšana nav atļauta, ja lauks :other ir :value',
    'prohibited_unless' => 'Lauka ":attribute" aizpildīšana nav atļauta, ja vien lauks :other nav :value',
    'prohibits' => 'Lauks ":attribute" neatļauj lauka :other aizpildīšanu',
    'regex' => 'Lauka ":attribute" vērtības formāts nav derīgs',
    'required' => 'Lauks ":attribute" jānorāda obligāti',
    'required_array_keys' => 'Laukam ":attribute" jāsatur vērtības: :values',
    'required_if' => 'Lauks ":attribute" jānorāda obligāti, ja lauka :other vērtība ir :value',
    'required_if_accepted' => 'Lauks ":attribute" jānorāda obligāti, ja lauks :other ir atzīmēts',
    'required_unless' => 'Lauks ":attribute" jānorāda obligāti, ja vien lauks :other nesatur :values',
    'required_with' => 'Lauks ":attribute" jānorāda obligāti, ja norāda :values',
    'required_with_all' => 'Lauks ":attribute" jānorāda obligāti, ja norāda :values',
    'required_without' => 'Lauks ":attribute" jānorāda obligāti, ja nenorāda :values',
    'required_without_all' => 'Lauks ":attribute" jānorāda obligāti, ja nav norādīta neviena no vērtībām: :values',
    'same' => 'Laukam ":attribute" jāsakrīt ar lauku :other',
    'size' => [
        'array' => 'Laukam ":attribute" jāsatur :size elementu',
        'file' => 'Laukam ":attribute" pievienotā faila izmēram jābūt :size kilobaitu',
        'numeric' => 'Lauka ":attribute" vērtības garumam jābūt :size',
        'string' => 'Lauka ":attribute" vērtības garumam jābūt :size rakstzīmju',
    ],
    'starts_with' => 'Lauka ":attribute" vērtībai jāsākas ar vienu no sekojošajām vērtībām: :values.',
    'string' => 'Lauka ":attribute" vērtībai jābūt rakstzīmju virknei',
    'timezone' => 'Laukam ":attribute" jāsatur derīga laika zona',
    'unique' => 'Lauka ":attribute" vērtība jau eksistē, lūdzu, izvēlieties unikālu vērtību',
    'uploaded' => 'Lauka ":attribute" augšupielāde neizdevās',
    'uppercase' => 'Lauka ":attribute" vērtībai jāsastāv no lielajiem burtiem',
    'url' => 'Laukam ":attribute" jāsatur derīga saite (URL)',
    'ulid' => 'Laukam ":attribute" jāsaturs derīgs ULID',
    'uuid' => 'Laukam ":attribute" jāsatur derīgs UUID',
    'phone' => 'Laukam ":attribute" jābūt derīgam telefona numuram',
    'iban' => 'Lauka :attribute vērtība nav derīgs IBAN starptautiskais bankas konta numurs',

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
        'instance' => [
            'not-found' => ':model ar identifikatoru :id netika atrasts',
        ],
        'model' => [
          'user' => 'lietotājs',
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
        'name' => 'Vārds (-i)',
        'surname' => 'Uzvārds (-i)',
        'person_code' => 'Personas kods',
        'birthdate' => 'Dzimšanas datums',
        'email' => 'E-pasta adrese',
        'iban_code' => 'IBAN',
        'password' => 'Parole',
        'phone' => 'Telefona numurs',

        'today' => 'šodienu',

        'user' => 'lietotājs',
    ],

    'exceptions' => [
      'country_required' => 'Laukā ":attribute" valsts koda norādīšana ir obligāta',
    ],

];
