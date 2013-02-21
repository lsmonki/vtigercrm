<?php
 /*+********************************************************************************
 * Terms & Conditions are placed on the: http://vtiger.com.pl
 ********************************************************************************
 *  Language		: Język Polski
 *  Vtiger Version	: 5.4.x
 *	Pack Version	: 1.13
 *  Author          : OpenSaaS Sp. z o.o. 
 *  Licence			: GPL
 *  Help/Email      : bok@opensaas.pl                                                                                                                 
 *  Website         : www.vtiger.com.pl, www.opensaas.pl
 ********************************************************************************+*/
$fallback = false;

$messages = array(

'HTMLPurifier' => 'HTML Oczyszczacz',

// for unit testing purposes
'LanguageFactoryTest: Pizza' => 'Pizza',
'LanguageTest: List' => '$1',
'LanguageTest: Hash' => '$1.Keys; $1.Values',

'Item separator' => ', ',
'Item separator last' => ' i ', // non-Harvard style

'ErrorCollector: No errors' => 'Brak wykrytych błędów. Jednakże, ponieważ raportowanie błędów Wciąż nie zostało zakończone mogły wystąpić błędy, że kolektor błędów nie został powiadomiony, prosimy sprawdzić starannie wyjścia HTML.',
'ErrorCollector: At line'   => ' w linii $line',
'ErrorCollector: Incidental errors'  => 'Przypadkowe błędy',

'Lexer: Unclosed comment'      => 'Niezamknięty komentarz',
'Lexer: Unescaped lt'          => 'Niecytowany znak mniejszości (<) powinien być &lt;',
'Lexer: Missing gt'            => 'Brakujący znak większości (>), poprzedni znak mniejszości (<) powinno się unikać',
'Lexer: Missing attribute key' => 'Deklaracja atrybutu nie ma klucza',
'Lexer: Missing end quote'     => 'Deklaracja atrybutu nie ma Końca cytatu',

'Strategy_RemoveForeignElements: Tag transform'              => '<$1> element przekształcony w $CurrentToken.',
'Strategy_RemoveForeignElements: Missing required attribute' => '$CurrentToken.Kompaktowy brakujący element wymaganego atrybutu $1',
'Strategy_RemoveForeignElements: Foreign element to text'    => 'Nierozpoznany $CurrentToken.Szeregowane słowa przekonwertowane na tekst',
'Strategy_RemoveForeignElements: Foreign element removed'    => 'Nierozpoznany $CurrentToken.Szeregowane słowa usunięte',
'Strategy_RemoveForeignElements: Comment removed'            => 'komentarz zawierający "$CurrentToken.Data" usunięty',
'Strategy_RemoveForeignElements: Foreign meta element removed' => 'Nierozpoznany $CurrentToken.Szeregowane meta tag i wszystkich potomków usunięte',
'Strategy_RemoveForeignElements: Token removed to end'       => 'Tagi i tekstu począwszy od $1 elementu, gdzie usunięte do końca',
'Strategy_RemoveForeignElements: Trailing hyphen in comment removed' => 'Końcowym łącznik komentarza usunięty',
'Strategy_RemoveForeignElements: Hyphens in comment collapsed' => 'Podwójne myślniki w komentarzach nie są dozwolone, a były przesunięte do pojedynczego łącznika',

'Strategy_MakeWellFormed: Unnecessary end tag removed' => 'Niepotrzebny $CurrentToken.Szeregowane słowa usunięte',
'Strategy_MakeWellFormed: Unnecessary end tag to text' => 'Niepotrzebny $CurrentToken.Szeregowane słowa przekonwertowane na tekst',
'Strategy_MakeWellFormed: Tag auto closed'             => '$1.Kompaktowanie zaczął w linii $1.Linia automatycznie zamknięta przez $CurrentToken.',
'Strategy_MakeWellFormed: Tag carryover'               => '$1.Kompaktowanie zaczął w linii $1.Linia automatycznie kontynuowana w $CurrentToken.',
'Strategy_MakeWellFormed: Stray end tag removed'       => 'Zbłąkany $CurrentToken.Szeregowane słowa usunięte',
'Strategy_MakeWellFormed: Stray end tag to text'       => 'Zbłąkany $CurrentToken.Szeregowane słowa przekonwertowane na tekst',
'Strategy_MakeWellFormed: Tag closed by element end'   => '$1.Kompaktowanie słowa zaczął w linii $1.Linia zamknięta do końca $CurrentToken.',
'Strategy_MakeWellFormed: Tag closed by document end'  => '$1.Kompaktowanie słowa zaczął w linii $1.Linia zamknięte do końca dokumentu',

'Strategy_FixNesting: Node removed'          => '$CurrentToken.Kompaktowanie węzeła usunięte',
'Strategy_FixNesting: Node excluded'         => '$CurrentToken.Kompaktowanie węzeła usunięty z powodu wykluczenia potomka przez element nadrzędny',
'Strategy_FixNesting: Node reorganized'      => 'Treść $CurrentToken.Kompaktowanie węzeła zreorganizowane, aby wymusić jego model zawartości',
'Strategy_FixNesting: Node contents removed' => 'Treść $CurrentToken.Kompaktowanie węzeła usunięte',

'AttrValidator: Attributes transformed' => 'Atrybuty $CurrentToken.Kompaktowanie przekształciło z $1.Keys to $2.Keys',
'AttrValidator: Attribute removed' => '$CurrentAttr.Nazwa atrybutu w $CurrentToken.Kompaktowanie usunięte',

);

$errorNames = array(
    E_ERROR   => 'Błąd',
    E_WARNING => 'Ostrzeżenie',
    E_NOTICE  => 'Ogłoszenie'
);

// vim: et sw=4 sts=4
