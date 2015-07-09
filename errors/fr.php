<?php
/**
 * :attribute => input name
 * :params => rule parameters ( eg: :params(0) = 10 of max_length(10) )
 */
return array(
    'required' => 'Le champ :attribute est obligatoire',
    'integer' => 'Le champ :attribute doit être un entier',
    'float' => 'Le champ :attribute doit être un décimal',
    'numeric' => 'Le champ :attribute doit être numérique',
    'email' => ':attribute n\'est pas une adresse email valide',
    'alpha' => 'Le champ :attribute doit être une valeur alpha',
    'alpha_numeric' => 'Le champ :attribute doit être une valeur alphanumérique',
    'ip' => ':attribute doit contenir une IP valide',
    'url' => ':attribute doit contenir une URL valide',
    'max_length' => 'Le champ :attribute peut avoir au maximum :params(0) caractères',
    'min_length' => 'Le champ :attribute doit avoir au minimum :params(0) caractères',
    'exact_length' => 'Le champ :attribute doit avoir :params(0) caractères',
    'equals' => 'Le champ :attribute doit être le même que :params(0)'
);
