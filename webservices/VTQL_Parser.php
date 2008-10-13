<?php
/* Driver template for the PHP_VTQL_ParserrGenerator parser generator. (PHP port of LEMON)
*/

/**
 * This can be used to store both the string representation of
 * a token, and any useful meta-data associated with the token.
 *
 * meta-data should be stored as an array
 */
class VTQL_ParseryyToken implements ArrayAccess
{
    public $string = '';
    public $metadata = array();

    function __construct($s, $m = array())
    {
        if ($s instanceof VTQL_ParseryyToken) {
            $this->string = $s->string;
            $this->metadata = $s->metadata;
        } else {
            $this->string = (string) $s;
            if ($m instanceof VTQL_ParseryyToken) {
                $this->metadata = $m->metadata;
            } elseif (is_array($m)) {
                $this->metadata = $m;
            }
        }
    }

    function __toString()
    {
        return $this->_string;
    }

    function offsetExists($offset)
    {
        return isset($this->metadata[$offset]);
    }

    function offsetGet($offset)
    {
        return $this->metadata[$offset];
    }

    function offsetSet($offset, $value)
    {
        if ($offset === null) {
            if (isset($value[0])) {
                $x = ($value instanceof VTQL_ParseryyToken) ?
                    $value->metadata : $value;
                $this->metadata = array_merge($this->metadata, $x);
                return;
            }
            $offset = count($this->metadata);
        }
        if ($value === null) {
            return;
        }
        if ($value instanceof VTQL_ParseryyToken) {
            if ($value->metadata) {
                $this->metadata[$offset] = $value->metadata;
            }
        } elseif ($value) {
            $this->metadata[$offset] = $value;
        }
    }

    function offsetUnset($offset)
    {
        unset($this->metadata[$offset]);
    }
}

/** The following structure represents a single element of the
 * parser's stack.  Information stored includes:
 *
 *   +  The state number for the parser at this level of the stack.
 *
 *   +  The value of the token stored at this level of the stack.
 *      (In other words, the "major" token.)
 *
 *   +  The semantic value stored at this level of the stack.  This is
 *      the information used by the action routines in the grammar.
 *      It is sometimes called the "minor" token.
 */
class VTQL_ParseryyStackEntry
{
    public $stateno;       /* The state-number */
    public $major;         /* The major token value.  This is the code
                     ** number for the token at this stack level */
    public $minor; /* The user-supplied minor token value.  This
                     ** is the value of the token  */
};

// code external to the class is included here

// declare_class is output here
#line 355 "e:\workspace\parsergenerator\VTQL_parser.y"
class VTQL_Parser#line 102 "e:\workspace\parsergenerator\VTQL_parser.php"
{
/* First off, code is included which follows the "include_class" declaration
** in the input file. */
#line 150 "e:\workspace\parsergenerator\VTQL_parser.y"

/*
sample format(for contacts) for generated sql object 
Array ( 
	[column_list] => c4,c3,c2,c1 
	[tableName] => vtiger_crmentity,vtiger_contactdetails,vtiger_contactaddress,vtiger_contactsubdetails,vtiger_contactscf,vtiger_customerdetails 
	[where_condition] => Array ( 
		[column_operators] => Array ( 
			[0] => = 
			[1] => = 
			[2] => = 
			) 
		[column_names] => Array ( 
			[0] => c1 
			[1] => c2 
			[2] => c3 
			) 
		[column_values] => Array ( 
			[0] => 'llet me' 
			[1] => 45 
			[2] => -1 
			) 
		//TO BE DONE
		[grouping] => Array (
			[0] => Array (
				[0] => 1
				[1] => 2
				)
			)
		[operators] => Array ( 
			[0] => and 
			[1] => or 
			)
		)
	[orderby] => Array ( 
		[0] => c4 
		[1] => c5 
		)
	[select] => SELECT 
	[from] => from 
	[semi_colon] => ; 
)*/
	private $out;
	public $lex;
	private $module_inst;
	private $success ;
	private $query ;
	private $exception;
	private $error_msg;
	private $syntax_error;
	private $user;
function __construct($user, $lex,$out)
{
    if(!is_array($out)){
    	$out = array();
    }
    $this->out = &$out;
    $this->lex = $lex;
    $this->success = false;
    $this->exception = false;
    $this->error_msg ='';
    $this->query = '';
    $this->syntax_error = false;
    $this->user = $user;
}
function __toString(){
return $this->value."";
}
function buildSelectStmt($sqlDump){
$meta = $sqlDump['meta'];
$fieldcol = $meta->getFieldColumnMapping();
$columnTable = $meta->getColumnTableMapping();
$this->query = 'SELECT ';
if(strcmp($sqlDump['column_list'],'*')===0){
$i=0;
foreach($fieldcol as $field=>$col){
if($i===0){
$this->query = $this->query.$columnTable[$col].'.'.$col;
$i++;
}else{
$this->query = $this->query.','.$columnTable[$col].'.'.$col;
}
}
}else{
$i=0;
foreach($sqlDump['column_list'] as $ind=>$field){
if(!$fieldcol[$field]){
$this->exception = true;
$this->error_msg = new WebServiceError(WebServiceErrorCode::$ACCESSDENIED, "Permission to access '.$field.' attribute denied.");
}
if($i===0){
$this->query = $this->query.$columnTable[$fieldcol[$field]].'.'.$fieldcol[$field];
$i++;
}else{
$this->query = $this->query.','.$columnTable[$fieldcol[$field]].'.'.$fieldcol[$field];
}
}
}
//$tables = $this->getTables($sqlDump,$columns);
$this->query = $this->query.' FROM '.$sqlDump['tableName'].$sqlDump['defaultJoinConditions'];
if($sqlDump['where_condition']){
if((sizeof($sqlDump['where_condition']['column_names']) == 
sizeof($sqlDump['where_condition']['column_values'])) && 
(sizeof($sqlDump['where_condition']['column_operators']) == sizeof($sqlDump['where_condition']['operators'])+1)){
$this->query = $this->query.' WHERE ';
$i=0;
$referenceFields = $meta->getReferenceFieldDetails();
for(;$i<sizeof($sqlDump['where_condition']['column_values']);++$i){
if(!$sqlDump['where_condition']['column_names'][$i]){
$this->exception = true;
$this->error_msg = new WebServiceError(WebServiceErrorCode::$ACCESSDENIED, "Permission to access ".$sqlDump['where_condition']['column_names'][$i]." attribute denied.");
}
$whereField = $sqlDump['where_condition']['column_names'][$i];
$whereOperator = $sqlDump['where_condition']['column_operators'][$i];
$whereValue = $sqlDump['where_condition']['column_values'][$i];
if(in_array($whereField,array_keys($referenceFields))){
if(strpos($whereValue,'x')!==false){
$whereValue = getIdComponents($whereValue);
$whereValue = $whereValue[1];
}else{
$this->exception = true;
$this->error_msg = new WebServiceError(WebServiceErrorCode::$INVALIDID,"Id specified is incorrect");
}
}
$this->query = $this->query.$columnTable[$fieldcol[$whereField]].'.'.$fieldcol[$whereField].$whereOperator.$whereValue;
if($i <sizeof($sqlDump['where_condition']['column_values'])-1){
$this->query = $this->query.' ';
$this->query = $this->query.$sqlDump['where_condition']['operators'][$i].' ';
}
}
}else{
$this->execption = true;
$this->error_msg = new WebServiceError(WebServiceErrorCode::$QUERYSYNTAX, "columns data inappropriate");
}
$this->query = $this->query." AND ";
}else{
$this->query = $this->query." WHERE ";
}
$this->query = $this->query."vtiger_crmentity.deleted=0";
if(strcasecmp("off",$this->user->is_admin)===0){
require('user_privileges/user_privileges_'.$this->user->id.'.php');
require('user_privileges/sharing_privileges_'.$this->user->id.'.php');
$this->query = $this->query." and (vtiger_crmentity.smownerid in({$this->user->id}) or vtiger_crmentity.smownerid in(select vtiger_user2role.userid from vtiger_user2role inner join vtiger_users on vtiger_users.id=vtiger_user2role.userid inner join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid where vtiger_role.parentrole like '".$current_user_parent_role_seq."::%') or vtiger_crmentity.smownerid in(select shareduserid from vtiger_tmp_read_user_sharing_per where userid=".$this->user->id." and tabid=".$meta->getObjectId()."))";
}
if($sqlDump['orderby']){
$i=0;
$this->query = $this->query.' ORDER BY ';
foreach($sqlDump['orderby'] as $ind=>$field){
if($i===0){
$this->query = $this->query.$columnTable[$fieldcol[$field]].".".$fieldcol[$field];
$i++;
}else{
$this->query = $this->query.','.$columnTable[$fieldcol[$field]].".".$fieldcol[$field];
}
}
}
if($sqlDump['limit']){
$i=0;
$offset =false;
if(sizeof($sqlDump['limit'])>1){
$offset = true;
}
$this->query = $this->query.' LIMIT ';
foreach($sqlDump['limit'] as $ind=>$field){
if(!$offset){
$field = ($field>100)? 100: $field;
}
if($i===0){
$this->query = $this->query.$field;
$i++;
$offset = false;
}else{
$this->query = $this->query.','.$field;
}
}
}else{
$this->query = $this->query.' LIMIT 100';
}
$this->query = $this->query.';';
}
function getTables($sqlDump,$columns){
$meta = $sqlDump['meta'];
$coltable = $meta->getColumnTableMapping();
$tables = array();
foreach($columns as $ind=>$col){
$tables[$coltable[$col]] = $coltable[$col];
}
$tables = array_keys($tables);
//print_r($tables);
return ($tables);
}
function isSuccess(){
return $this->success;
}
function getErrorMsg(){
return $this->error_msg;
}
function getQuery(){
return $this->query;
}
function getObjectMetaData(){
return $this->out['meta'];
}
#line 311 "e:\workspace\parsergenerator\VTQL_parser.php"

/* Next is all token values, as class constants
*/
/* 
** These constants (all generated automatically by the parser generator)
** specify the various kinds of tokens (terminals) that the parser
** understands. 
**
** Each symbol here is a terminal symbol in the grammar.
*/
    const LPAREN                         =  1;
    const RPAREN                         =  2;
    const SELECT                         =  3;
    const FRM                            =  4;
    const COLUMNNAME                     =  5;
    const ASTERISK                       =  6;
    const COMMA                          =  7;
    const TABLENAME                      =  8;
    const WHERE                          =  9;
    const LOGICAL_AND                    = 10;
    const LOGICAL_OR                     = 11;
    const VALUE                          = 12;
    const EQ                             = 13;
    const LT                             = 14;
    const GT                             = 15;
    const LTE                            = 16;
    const GTE                            = 17;
    const NE                             = 18;
    const ORDERBY                        = 19;
    const LIMIT                          = 20;
    const SEMICOLON                      = 21;
    const YY_NO_ACTION = 87;
    const YY_ACCEPT_ACTION = 86;
    const YY_ERROR_ACTION = 85;

/* Next are that tables used to determine what action to take based on the
** current state and lookahead token.  These tables are used to implement
** functions that take a state number and lookahead value and return an
** action integer.  
**
** Suppose the action integer is N.  Then the action is determined as
** follows
**
**   0 <= N < self::YYNSTATE                              Shift N.  That is,
**                                                        push the lookahead
**                                                        token onto the stack
**                                                        and goto state N.
**
**   self::YYNSTATE <= N < self::YYNSTATE+self::YYNRULE   Reduce by rule N-YYNSTATE.
**
**   N == self::YYNSTATE+self::YYNRULE                    A syntax error has occurred.
**
**   N == self::YYNSTATE+self::YYNRULE+1                  The parser accepts its
**                                                        input. (and concludes parsing)
**
**   N == self::YYNSTATE+self::YYNRULE+2                  No such action.  Denotes unused
**                                                        slots in the yy_action[] table.
**
** The action table is constructed as a single large static array $yy_action.
** Given state S and lookahead X, the action is computed as
**
**      self::$yy_action[self::$yy_shift_ofst[S] + X ]
**
** If the index value self::$yy_shift_ofst[S]+X is out of range or if the value
** self::$yy_lookahead[self::$yy_shift_ofst[S]+X] is not equal to X or if
** self::$yy_shift_ofst[S] is equal to self::YY_SHIFT_USE_DFLT, it means that
** the action is not in the table and that self::$yy_default[S] should be used instead.  
**
** The formula above is for computing the action when the lookahead is
** a terminal symbol.  If the lookahead is a non-terminal (as occurs after
** a reduce action) then the static $yy_reduce_ofst array is used in place of
** the static $yy_shift_ofst array and self::YY_REDUCE_USE_DFLT is used in place of
** self::YY_SHIFT_USE_DFLT.
**
** The following are the tables generated in this section:
**
**  self::$yy_action        A single table containing all actions.
**  self::$yy_lookahead     A table containing the lookahead for each entry in
**                          yy_action.  Used to detect hash collisions.
**  self::$yy_shift_ofst    For each state, the offset into self::$yy_action for
**                          shifting terminals.
**  self::$yy_reduce_ofst   For each state, the offset into self::$yy_action for
**                          shifting non-terminals after a reduce.
**  self::$yy_default       Default action for each state.
*/
    const YY_SZ_ACTTAB = 48;
static public $yy_action = array(
 /*     0 */    26,   24,   27,   23,   22,   28,   86,   36,   31,   34,
 /*    10 */     9,   19,   18,   12,   14,   11,   32,   46,   45,    3,
 /*    20 */    16,   33,   15,    6,    5,   10,   13,   37,   40,    2,
 /*    30 */    21,    8,   41,   25,   29,    1,    4,   43,   17,    7,
 /*    40 */    35,   30,   38,   44,   20,   47,   39,   42,
    );
    static public $yy_lookahead = array(
 /*     0 */    13,   14,   15,   16,   17,   18,   23,   24,   25,   26,
 /*    10 */    40,   41,   42,    4,   29,   31,    7,   10,   11,    9,
 /*    20 */    35,    8,   36,   37,   19,   32,   30,    7,   12,    3,
 /*    30 */     7,   33,    5,   12,    5,   27,    5,    1,   39,   20,
 /*    40 */    38,    6,   28,   21,   12,   34,    2,   43,
);
    const YY_SHIFT_USE_DFLT = -14;
    const YY_SHIFT_MAX = 21;
    static public $yy_shift_ofst = array(
 /*     0 */    26,  -14,   35,  -14,  -13,   36,   31,   32,   22,   44,
 /*    10 */    19,    5,   13,   10,    9,    7,   29,   21,   27,   20,
 /*    20 */    23,   16,
);
    const YY_REDUCE_USE_DFLT = -31;
    const YY_REDUCE_MAX = 13;
    static public $yy_reduce_ofst = array(
 /*     0 */   -17,  -30,  -15,  -14,   -1,    8,    2,    4,   11,   14,
 /*    10 */    -2,   -7,   -4,  -16,
);
    static public $yyExpectedTokens = array(
        /* 0 */ array(3, ),
        /* 1 */ array(),
        /* 2 */ array(6, ),
        /* 3 */ array(),
        /* 4 */ array(13, 14, 15, 16, 17, 18, ),
        /* 5 */ array(1, ),
        /* 6 */ array(5, ),
        /* 7 */ array(12, ),
        /* 8 */ array(21, ),
        /* 9 */ array(2, ),
        /* 10 */ array(20, ),
        /* 11 */ array(19, ),
        /* 12 */ array(8, ),
        /* 13 */ array(9, ),
        /* 14 */ array(4, 7, ),
        /* 15 */ array(10, 11, ),
        /* 16 */ array(5, ),
        /* 17 */ array(12, ),
        /* 18 */ array(5, ),
        /* 19 */ array(7, ),
        /* 20 */ array(7, ),
        /* 21 */ array(12, ),
        /* 22 */ array(),
        /* 23 */ array(),
        /* 24 */ array(),
        /* 25 */ array(),
        /* 26 */ array(),
        /* 27 */ array(),
        /* 28 */ array(),
        /* 29 */ array(),
        /* 30 */ array(),
        /* 31 */ array(),
        /* 32 */ array(),
        /* 33 */ array(),
        /* 34 */ array(),
        /* 35 */ array(),
        /* 36 */ array(),
        /* 37 */ array(),
        /* 38 */ array(),
        /* 39 */ array(),
        /* 40 */ array(),
        /* 41 */ array(),
        /* 42 */ array(),
        /* 43 */ array(),
        /* 44 */ array(),
        /* 45 */ array(),
        /* 46 */ array(),
        /* 47 */ array(),
);
    static public $yy_default = array(
 /*     0 */    85,   79,   59,   66,   85,   52,   85,   85,   85,   54,
 /*    10 */    81,   75,   85,   62,   85,   61,   85,   85,   85,   76,
 /*    20 */    82,   85,   72,   71,   69,   67,   68,   70,   73,   56,
 /*    30 */    57,   49,   58,   60,   50,   63,   48,   78,   74,   53,
 /*    40 */    83,   77,   80,   51,   84,   65,   64,   55,
);
/* The next thing included is series of defines which control
** various aspects of the generated parser.
**    self::YYNOCODE      is a number which corresponds
**                        to no legal terminal or nonterminal number.  This
**                        number is used to fill in empty slots of the hash 
**                        table.
**    self::YYFALLBACK    If defined, this indicates that one or more tokens
**                        have fall-back values which should be used if the
**                        original value of the token will not parse.
**    self::YYSTACKDEPTH  is the maximum depth of the parser's stack.
**    self::YYNSTATE      the combined number of states.
**    self::YYNRULE       the number of rules in the grammar
**    self::YYERRORSYMBOL is the code number of the error symbol.  If not
**                        defined, then do no error processing.
*/
    const YYNOCODE = 45;
    const YYSTACKDEPTH = 100;
    const YYNSTATE = 48;
    const YYNRULE = 37;
    const YYERRORSYMBOL = 22;
    const YYERRSYMDT = 'yy0';
    const YYFALLBACK = 0;
    /** The next table maps tokens into fallback tokens.  If a construct
     * like the following:
     * 
     *      %fallback ID X Y Z.
     *
     * appears in the grammer, then ID becomes a fallback token for X, Y,
     * and Z.  Whenever one of the tokens X, Y, or Z is input to the parser
     * but it does not parse, the type of the token is changed to ID and
     * the parse is retried before an error is thrown.
     */
    static public $yyFallback = array(
    );
    /**
     * Turn parser tracing on by giving a stream to which to write the trace
     * and a prompt to preface each trace message.  Tracing is turned off
     * by making either argument NULL 
     *
     * Inputs:
     * 
     * - A stream resource to which trace output should be written.
     *   If NULL, then tracing is turned off.
     * - A prefix string written at the beginning of every
     *   line of trace output.  If NULL, then tracing is
     *   turned off.
     *
     * Outputs:
     * 
     * - None.
     * @param resource
     * @param string
     */
    static function Trace($TraceFILE, $zTracePrompt)
    {
        if (!$TraceFILE) {
            $zTracePrompt = 0;
        } elseif (!$zTracePrompt) {
            $TraceFILE = 0;
        }
        self::$yyTraceFILE = $TraceFILE;
        self::$yyTracePrompt = $zTracePrompt;
    }

    /**
     * Output debug information to output (php://output stream)
     */
    static function PrintTrace()
    {
        self::$yyTraceFILE = fopen('php://output', 'w');
        self::$yyTracePrompt = '';
    }

    /**
     * @var resource|0
     */
    static public $yyTraceFILE;
    /**
     * String to prepend to debug output
     * @var string|0
     */
    static public $yyTracePrompt;
    /**
     * @var int
     */
    public $yyidx;                    /* Index of top element in stack */
    /**
     * @var int
     */
    public $yyerrcnt;                 /* Shifts left before out of the error */
    /**
     * @var array
     */
    public $yystack = array();  /* The parser's stack */

    /**
     * For tracing shifts, the names of all terminals and nonterminals
     * are required.  The following table supplies these names
     * @var array
     */
    static public $yyTokenName = array( 
  '$',             'LPAREN',        'RPAREN',        'SELECT',      
  'FRM',           'COLUMNNAME',    'ASTERISK',      'COMMA',       
  'TABLENAME',     'WHERE',         'LOGICAL_AND',   'LOGICAL_OR',  
  'VALUE',         'EQ',            'LT',            'GT',          
  'LTE',           'GTE',           'NE',            'ORDERBY',     
  'LIMIT',         'SEMICOLON',     'error',         'sql',         
  'data_operator',  'select_clause',  'select_statement',  'lparen',      
  'rparen',        'selectcol_list',  'table_list',    'where_condition',
  'order_clause',  'limit_clause',  'end_stmt',      'selectcolumn_exp',
  'condition',     'expr_set',      'expr',          'logical_term',
  'column_group',  'column_list',   'column_exp',    'limit_set',   
    );

    /**
     * For tracing reduce actions, the names of all rules are required.
     * @var array
     */
    static public $yyRuleName = array(
 /*   0 */ "sql ::= data_operator",
 /*   1 */ "data_operator ::= select_clause",
 /*   2 */ "select_clause ::= select_statement",
 /*   3 */ "lparen ::= LPAREN",
 /*   4 */ "lparen ::=",
 /*   5 */ "rparen ::= RPAREN",
 /*   6 */ "rparen ::=",
 /*   7 */ "select_statement ::= SELECT selectcol_list FRM table_list where_condition order_clause limit_clause end_stmt",
 /*   8 */ "selectcol_list ::= selectcolumn_exp COLUMNNAME",
 /*   9 */ "selectcol_list ::= ASTERISK",
 /*  10 */ "selectcolumn_exp ::= selectcol_list COMMA",
 /*  11 */ "selectcolumn_exp ::=",
 /*  12 */ "table_list ::= TABLENAME",
 /*  13 */ "where_condition ::= WHERE condition",
 /*  14 */ "where_condition ::=",
 /*  15 */ "condition ::= expr_set expr",
 /*  16 */ "expr_set ::= condition LOGICAL_AND",
 /*  17 */ "expr_set ::= condition LOGICAL_OR",
 /*  18 */ "expr_set ::=",
 /*  19 */ "expr ::= COLUMNNAME logical_term VALUE",
 /*  20 */ "logical_term ::= EQ",
 /*  21 */ "logical_term ::= LT",
 /*  22 */ "logical_term ::= GT",
 /*  23 */ "logical_term ::= LTE",
 /*  24 */ "logical_term ::= GTE",
 /*  25 */ "logical_term ::= NE",
 /*  26 */ "order_clause ::= ORDERBY lparen column_group rparen",
 /*  27 */ "order_clause ::=",
 /*  28 */ "column_group ::= column_list",
 /*  29 */ "column_list ::= column_exp COLUMNNAME",
 /*  30 */ "column_exp ::= column_list COMMA",
 /*  31 */ "column_exp ::=",
 /*  32 */ "limit_clause ::= LIMIT limit_set",
 /*  33 */ "limit_clause ::=",
 /*  34 */ "limit_set ::= VALUE",
 /*  35 */ "limit_set ::= VALUE COMMA VALUE",
 /*  36 */ "end_stmt ::= SEMICOLON",
    );

    /**
     * This function returns the symbolic name associated with a token
     * value.
     * @param int
     * @return string
     */
    function tokenName($tokenType)
    {
        if ($tokenType === 0) {
            return 'End of Input';
        }
        if ($tokenType > 0 && $tokenType < count(self::$yyTokenName)) {
            return self::$yyTokenName[$tokenType];
        } else {
            return "Unknown";
        }
    }

    /**
     * The following function deletes the value associated with a
     * symbol.  The symbol can be either a terminal or nonterminal.
     * @param int the symbol code
     * @param mixed the symbol's value
     */
    static function yy_destructor($yymajor, $yypminor)
    {
        switch ($yymajor) {
        /* Here is inserted the actions which take place when a
        ** terminal or non-terminal is destroyed.  This can happen
        ** when the symbol is popped from the stack during a
        ** reduce or during error processing or when a parser is 
        ** being destroyed before it is finished parsing.
        **
        ** Note: during a reduce, the only symbols destroyed are those
        ** which appear on the RHS of the rule, but which are not used
        ** inside the C code.
        */
            default:  break;   /* If no destructor action specified: do nothing */
        }
    }

    /**
     * Pop the parser's stack once.
     *
     * If there is a destructor routine associated with the token which
     * is popped from the stack, then call it.
     *
     * Return the major token number for the symbol popped.
     * @param VTQL_ParseryyParser
     * @return int
     */
    function yy_pop_parser_stack()
    {
        if (!count($this->yystack)) {
            return;
        }
        $yytos = array_pop($this->yystack);
        if (self::$yyTraceFILE && $this->yyidx >= 0) {
            fwrite(self::$yyTraceFILE,
                self::$yyTracePrompt . 'Popping ' . self::$yyTokenName[$yytos->major] .
                    "\n");
        }
        $yymajor = $yytos->major;
        self::yy_destructor($yymajor, $yytos->minor);
        $this->yyidx--;
        return $yymajor;
    }

    /**
     * Deallocate and destroy a parser.  Destructors are all called for
     * all stack elements before shutting the parser down.
     */
    function __destruct()
    {
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        if (is_resource(self::$yyTraceFILE)) {
            fclose(self::$yyTraceFILE);
        }
    }

    /**
     * Based on the current state and parser stack, get a list of all
     * possible lookahead tokens
     * @param int
     * @return array
     */
    function yy_get_expected_tokens($token)
    {
        $state = $this->yystack[$this->yyidx]->stateno;
        $expected = self::$yyExpectedTokens[$state];
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return $expected;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return array_unique($expected);
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate])) {
                        $expected += self::$yyExpectedTokens[$nextstate];
                            if (in_array($token,
                                  self::$yyExpectedTokens[$nextstate], true)) {
                            $this->yyidx = $yyidx;
                            $this->yystack = $stack;
                            return array_unique($expected);
                        }
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new VTQL_ParseryyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return array_unique($expected);
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return $expected;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        return array_unique($expected);
    }

    /**
     * Based on the parser state and current parser stack, determine whether
     * the lookahead token is possible.
     * 
     * The parser will convert the token value to an error token if not.  This
     * catches some unusual edge cases where the parser would fail.
     * @param int
     * @return bool
     */
    function yy_is_expected_token($token)
    {
        if ($token === 0) {
            return true; // 0 is not part of this
        }
        $state = $this->yystack[$this->yyidx]->stateno;
        if (in_array($token, self::$yyExpectedTokens[$state], true)) {
            return true;
        }
        $stack = $this->yystack;
        $yyidx = $this->yyidx;
        do {
            $yyact = $this->yy_find_shift_action($token);
            if ($yyact >= self::YYNSTATE && $yyact < self::YYNSTATE + self::YYNRULE) {
                // reduce action
                $done = 0;
                do {
                    if ($done++ == 100) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // too much recursion prevents proper detection
                        // so give up
                        return true;
                    }
                    $yyruleno = $yyact - self::YYNSTATE;
                    $this->yyidx -= self::$yyRuleInfo[$yyruleno]['rhs'];
                    $nextstate = $this->yy_find_reduce_action(
                        $this->yystack[$this->yyidx]->stateno,
                        self::$yyRuleInfo[$yyruleno]['lhs']);
                    if (isset(self::$yyExpectedTokens[$nextstate]) &&
                          in_array($token, self::$yyExpectedTokens[$nextstate], true)) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        return true;
                    }
                    if ($nextstate < self::YYNSTATE) {
                        // we need to shift a non-terminal
                        $this->yyidx++;
                        $x = new VTQL_ParseryyStackEntry;
                        $x->stateno = $nextstate;
                        $x->major = self::$yyRuleInfo[$yyruleno]['lhs'];
                        $this->yystack[$this->yyidx] = $x;
                        continue 2;
                    } elseif ($nextstate == self::YYNSTATE + self::YYNRULE + 1) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        if (!$token) {
                            // end of input: this is valid
                            return true;
                        }
                        // the last token was just ignored, we can't accept
                        // by ignoring input, this is in essence ignoring a
                        // syntax error!
                        return false;
                    } elseif ($nextstate === self::YY_NO_ACTION) {
                        $this->yyidx = $yyidx;
                        $this->yystack = $stack;
                        // input accepted, but not shifted (I guess)
                        return true;
                    } else {
                        $yyact = $nextstate;
                    }
                } while (true);
            }
            break;
        } while (true);
        return true;
    }

    /**
     * Find the appropriate action for a parser given the terminal
     * look-ahead token iLookAhead.
     *
     * If the look-ahead token is YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return YY_NO_ACTION.
     * @param int The look-ahead token
     */
    function yy_find_shift_action($iLookAhead)
    {
        $stateno = $this->yystack[$this->yyidx]->stateno;
     
        /* if ($this->yyidx < 0) return self::YY_NO_ACTION;  */
        if (!isset(self::$yy_shift_ofst[$stateno])) {
            // no shift actions
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_shift_ofst[$stateno];
        if ($i === self::YY_SHIFT_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            if (count(self::$yyFallback) && $iLookAhead < count(self::$yyFallback)
                   && ($iFallback = self::$yyFallback[$iLookAhead]) != 0) {
                if (self::$yyTraceFILE) {
                    fwrite(self::$yyTraceFILE, self::$yyTracePrompt . "FALLBACK " .
                        self::$yyTokenName[$iLookAhead] . " => " .
                        self::$yyTokenName[$iFallback] . "\n");
                }
                return $this->yy_find_shift_action($iFallback);
            }
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Find the appropriate action for a parser given the non-terminal
     * look-ahead token $iLookAhead.
     *
     * If the look-ahead token is self::YYNOCODE, then check to see if the action is
     * independent of the look-ahead.  If it is, return the action, otherwise
     * return self::YY_NO_ACTION.
     * @param int Current state number
     * @param int The look-ahead token
     */
    function yy_find_reduce_action($stateno, $iLookAhead)
    {
        /* $stateno = $this->yystack[$this->yyidx]->stateno; */

        if (!isset(self::$yy_reduce_ofst[$stateno])) {
            return self::$yy_default[$stateno];
        }
        $i = self::$yy_reduce_ofst[$stateno];
        if ($i == self::YY_REDUCE_USE_DFLT) {
            return self::$yy_default[$stateno];
        }
        if ($iLookAhead == self::YYNOCODE) {
            return self::YY_NO_ACTION;
        }
        $i += $iLookAhead;
        if ($i < 0 || $i >= self::YY_SZ_ACTTAB ||
              self::$yy_lookahead[$i] != $iLookAhead) {
            return self::$yy_default[$stateno];
        } else {
            return self::$yy_action[$i];
        }
    }

    /**
     * Perform a shift action.
     * @param int The new state to shift in
     * @param int The major token to shift in
     * @param mixed the minor token to shift in
     */
    function yy_shift($yyNewState, $yyMajor, $yypMinor)
    {
        $this->yyidx++;
        if ($this->yyidx >= self::YYSTACKDEPTH) {
            $this->yyidx--;
            if (self::$yyTraceFILE) {
                fprintf(self::$yyTraceFILE, "%sStack Overflow!\n", self::$yyTracePrompt);
            }
            while ($this->yyidx >= 0) {
                $this->yy_pop_parser_stack();
            }
            /* Here code is inserted which will execute if the parser
            ** stack ever overflows */
#line 368 "e:\workspace\parsergenerator\VTQL_parser.y"

	$this->error_msg = new WebServiceError(WebServiceErrorCode::$QUERYSYNTAX, "Parser stack overflow");
#line 973 "e:\workspace\parsergenerator\VTQL_parser.php"
            return;
        }
        $yytos = new VTQL_ParseryyStackEntry;
        $yytos->stateno = $yyNewState;
        $yytos->major = $yyMajor;
        $yytos->minor = $yypMinor;
        array_push($this->yystack, $yytos);
        if (self::$yyTraceFILE && $this->yyidx > 0) {
            fprintf(self::$yyTraceFILE, "%sShift %d\n", self::$yyTracePrompt,
                $yyNewState);
            fprintf(self::$yyTraceFILE, "%sStack:", self::$yyTracePrompt);
            for($i = 1; $i <= $this->yyidx; $i++) {
                fprintf(self::$yyTraceFILE, " %s",
                    self::$yyTokenName[$this->yystack[$i]->major]);
            }
            fwrite(self::$yyTraceFILE,"\n");
        }
    }

    /**
     * The following table contains information about every rule that
     * is used during the reduce.
     *
     * <pre>
     * array(
     *  array(
     *   int $lhs;         Symbol on the left-hand side of the rule
     *   int $nrhs;     Number of right-hand side symbols in the rule
     *  ),...
     * );
     * </pre>
     */
    static public $yyRuleInfo = array(
  array( 'lhs' => 23, 'rhs' => 1 ),
  array( 'lhs' => 24, 'rhs' => 1 ),
  array( 'lhs' => 25, 'rhs' => 1 ),
  array( 'lhs' => 27, 'rhs' => 1 ),
  array( 'lhs' => 27, 'rhs' => 0 ),
  array( 'lhs' => 28, 'rhs' => 1 ),
  array( 'lhs' => 28, 'rhs' => 0 ),
  array( 'lhs' => 26, 'rhs' => 8 ),
  array( 'lhs' => 29, 'rhs' => 2 ),
  array( 'lhs' => 29, 'rhs' => 1 ),
  array( 'lhs' => 35, 'rhs' => 2 ),
  array( 'lhs' => 35, 'rhs' => 0 ),
  array( 'lhs' => 30, 'rhs' => 1 ),
  array( 'lhs' => 31, 'rhs' => 2 ),
  array( 'lhs' => 31, 'rhs' => 0 ),
  array( 'lhs' => 36, 'rhs' => 2 ),
  array( 'lhs' => 37, 'rhs' => 2 ),
  array( 'lhs' => 37, 'rhs' => 2 ),
  array( 'lhs' => 37, 'rhs' => 0 ),
  array( 'lhs' => 38, 'rhs' => 3 ),
  array( 'lhs' => 39, 'rhs' => 1 ),
  array( 'lhs' => 39, 'rhs' => 1 ),
  array( 'lhs' => 39, 'rhs' => 1 ),
  array( 'lhs' => 39, 'rhs' => 1 ),
  array( 'lhs' => 39, 'rhs' => 1 ),
  array( 'lhs' => 39, 'rhs' => 1 ),
  array( 'lhs' => 32, 'rhs' => 4 ),
  array( 'lhs' => 32, 'rhs' => 0 ),
  array( 'lhs' => 40, 'rhs' => 1 ),
  array( 'lhs' => 41, 'rhs' => 2 ),
  array( 'lhs' => 42, 'rhs' => 2 ),
  array( 'lhs' => 42, 'rhs' => 0 ),
  array( 'lhs' => 33, 'rhs' => 2 ),
  array( 'lhs' => 33, 'rhs' => 0 ),
  array( 'lhs' => 43, 'rhs' => 1 ),
  array( 'lhs' => 43, 'rhs' => 3 ),
  array( 'lhs' => 34, 'rhs' => 1 ),
    );

    /**
     * The following table contains a mapping of reduce action to method name
     * that handles the reduction.
     * 
     * If a rule is not set, it has no handler.
     */
    static public $yyReduceMap = array(
        7 => 7,
        8 => 8,
        9 => 9,
        12 => 12,
        16 => 16,
        17 => 16,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        29 => 29,
        34 => 34,
        35 => 35,
        36 => 36,
    );
    /* Beginning here are the reduction cases.  A typical example
    ** follows:
    **  #line <lineno> <grammarfile>
    **   function yy_r0($yymsp){ ... }           // User supplied code
    **  #line <lineno> <thisfile>
    */
#line 11 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r7(){ 
if($this->yystack[$this->yyidx + -7]->minor){
$this->out['select'] = $this->yystack[$this->yyidx + -7]->minor;
}
if($this->yystack[$this->yyidx + -5]->minor){
$this->out['from'] = $this->yystack[$this->yyidx + -5]->minor ;
}
if(SEMI){
$this->out['semi_colon'] = SEMI;
}
if($this->out['select']){
$this->buildSelectStmt($this->out);
}
    }
#line 1092 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 25 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r8(){ 
$this->out['column_list'][] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1097 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 28 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r9(){
$this->out['column_list'] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1102 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 33 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r12(){
if($this->out["column_list"] !=="*"){
if(!in_array("id",$this->out["column_list"])){
	$this->out["column_list"][] = "id";
} 
}
$moduleName = $this->yystack[$this->yyidx + 0]->minor;
if(!$moduleName){
	$this->syntax_error = true;
	$this->error_msg = new WebServiceError(WebServiceErrorCode::$QUERYSYNTAX, "There is an syntax error in query");
	return;
}
$inst = new VtigerCRMObject($moduleName,false);
$inst = $inst->getInstance();
$this->module_instance = $inst;
$this->out['moduleName'] = $moduleName;
$this->out['tableName'] = implode(',',$inst->tab_name);
    }
#line 1122 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 54 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r16(){
$this->out['where_condition']['operators'][] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1127 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 61 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r19(){ 
if(strcmp($this->yystack[$this->yyidx + -2]->minor, 'id')===0){
$this->yystack[$this->yyidx + 0]->minor = getIdComponents($this->yystack[$this->yyidx + 0]->minor);
$this->yystack[$this->yyidx + 0]->minor = $this->yystack[$this->yyidx + 0]->minor[1];
}
$this->out['where_condition']['column_names'][] = $this->yystack[$this->yyidx + -2]->minor;
$this->out['where_condition']['column_values'][] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1137 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 69 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r20(){
$this->out['where_condition']['column_operators'][] = '=';
    }
#line 1142 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 72 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r21(){
$this->out['where_condition']['column_operators'][] = '<';
    }
#line 1147 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 75 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r22(){
$this->out['where_condition']['column_operators'][] = '>';
    }
#line 1152 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 78 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r23(){
$this->out['where_condition']['column_operators'][] = '<=';
    }
#line 1157 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 81 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r24(){
$this->out['where_condition']['column_operators'][] = '>=';
    }
#line 1162 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 84 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r25(){
$this->out['where_condition']['column_operators'][] = '!=';
    }
#line 1167 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 90 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r29(){
$this->out['orderby'][] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1172 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 97 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r34(){
$this->out['limit'][] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1177 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 100 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r35(){
$this->out['limit'][] = $this->yystack[$this->yyidx + -2]->minor;
$this->out['limit'][] = $this->yystack[$this->yyidx + 0]->minor;
    }
#line 1183 "e:\workspace\parsergenerator\VTQL_parser.php"
#line 104 "e:\workspace\parsergenerator\VTQL_parser.y"
    function yy_r36(){ 
if(!$this->out['meta']){
//TODO tmp fix remove this.

$module = $this->out['moduleName'];
$objectMeta = new VtigerCRMObjectMeta(new VtigerCRMObject($module,false),$this->user);
$objectMeta->retrieveMeta();
$this->out['meta'] = $objectMeta;
$meta = $this->out['meta'];
$fieldcol = $meta->getFieldColumnMapping();
$columns = array();
if(strcmp($this->out['column_list'],'*')===0){
$columns = array_values($fieldcol);
}else{
foreach($this->out['column_list'] as $ind=>$field){
$columns[] = $fieldcol[$field];
}
}
if($this->out['where_condition']){
foreach($this->out['where_condition']['column_names'] as $ind=>$field){
$columns[] = $fieldcol[$field];
}
}
$tables = $this->getTables($this->out, $columns);
if(!in_array("vtiger_crmentity",$tables)){
array_push($tables,"vtiger_crmentity");
}
$module = $this->module_instance;
$firstTable = $module->table_name;
$firstIndex = $module->tab_name_index[$firstTable];
foreach($tables as $ind=>$table){
if($module->table_name!=$table){
	$this->out['defaultJoinConditions'] = $this->out['defaultJoinConditions'].' LEFT JOIN '.$table.' ON '.$table.'.'.$module->tab_name_index[$table].'='.$firstTable.'.'.$firstIndex;
}else{
	$this->out['tableName'] = $table;
}
}
}
/*
$module = $this->module_instance;
foreach($module->tab_name_index as $key=>$val){
ECNAME = $key.$val;
break;
}
*/
    }
#line 1231 "e:\workspace\parsergenerator\VTQL_parser.php"

    /**
     * placeholder for the left hand side in a reduce operation.
     * 
     * For a parser with a rule like this:
     * <pre>
     * rule(A) ::= B. { A = 1; }
     * </pre>
     * 
     * The parser will translate to something like:
     * 
     * <code>
     * function yy_r0(){$this->_retvalue = 1;}
     * </code>
     */
    private $_retvalue;

    /**
     * Perform a reduce action and the shift that must immediately
     * follow the reduce.
     * 
     * For a rule such as:
     * 
     * <pre>
     * A ::= B blah C. { dosomething(); }
     * </pre>
     * 
     * This function will first call the action, if any, ("dosomething();" in our
     * example), and then it will pop three states from the stack,
     * one for each entry on the right-hand side of the expression
     * (B, blah, and C in our example rule), and then push the result of the action
     * back on to the stack with the resulting state reduced to (as described in the .out
     * file)
     * @param int Number of the rule by which to reduce
     */
    function yy_reduce($yyruleno)
    {
        //int $yygoto;                     /* The next state */
        //int $yyact;                      /* The next action */
        //mixed $yygotominor;        /* The LHS of the rule reduced */
        //VTQL_ParseryyStackEntry $yymsp;            /* The top of the parser's stack */
        //int $yysize;                     /* Amount to pop the stack */
        $yymsp = $this->yystack[$this->yyidx];
        if (self::$yyTraceFILE && $yyruleno >= 0 
              && $yyruleno < count(self::$yyRuleName)) {
            fprintf(self::$yyTraceFILE, "%sReduce (%d) [%s].\n",
                self::$yyTracePrompt, $yyruleno,
                self::$yyRuleName[$yyruleno]);
        }

        $this->_retvalue = $yy_lefthand_side = null;
        if (array_key_exists($yyruleno, self::$yyReduceMap)) {
            // call the action
            $this->_retvalue = null;
            $this->{'yy_r' . self::$yyReduceMap[$yyruleno]}();
            $yy_lefthand_side = $this->_retvalue;
        }
        $yygoto = self::$yyRuleInfo[$yyruleno]['lhs'];
        $yysize = self::$yyRuleInfo[$yyruleno]['rhs'];
        $this->yyidx -= $yysize;
        for($i = $yysize; $i; $i--) {
            // pop all of the right-hand side parameters
            array_pop($this->yystack);
        }
        $yyact = $this->yy_find_reduce_action($this->yystack[$this->yyidx]->stateno, $yygoto);
        if ($yyact < self::YYNSTATE) {
            /* If we are not debugging and the reduce action popped at least
            ** one element off the stack, then we can push the new element back
            ** onto the stack here, and skip the stack overflow test in yy_shift().
            ** That gives a significant speed improvement. */
            if (!self::$yyTraceFILE && $yysize) {
                $this->yyidx++;
                $x = new VTQL_ParseryyStackEntry;
                $x->stateno = $yyact;
                $x->major = $yygoto;
                $x->minor = $yy_lefthand_side;
                $this->yystack[$this->yyidx] = $x;
            } else {
                $this->yy_shift($yyact, $yygoto, $yy_lefthand_side);
            }
        } elseif ($yyact == self::YYNSTATE + self::YYNRULE + 1) {
            $this->yy_accept();
        }
    }

    /**
     * The following code executes when the parse fails
     * 
     * Code from %parse_fail is inserted here
     */
    function yy_parse_failed()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sFail!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser fails */
#line 362 "e:\workspace\parsergenerator\VTQL_parser.y"

	if(!$this->syntax_error){
		$this->error_msg = new WebServiceError(WebServiceErrorCode::$QUERYSYNTAX, "Parsing failed");
	}
#line 1338 "e:\workspace\parsergenerator\VTQL_parser.php"
    }

    /**
     * The following code executes when a syntax error first occurs.
     * 
     * %syntax_error code is inserted here
     * @param int The major type of the error token
     * @param mixed The minor type of the error token
     */
    function yy_syntax_error($yymajor, $TOKEN)
    {
#line 372 "e:\workspace\parsergenerator\VTQL_parser.y"

    $synMsg = "Syntax Error on line " . $this->lex->linenum . ": token '" .$this->lex->value."' ";
    /*foreach ($this->yystack as $entry) {
        $this->error_msg =$this->error_msg.$this->tokenName($entry->major) . ' ';
    }*/
    foreach ($this->yy_get_expected_tokens($yymajor) as $token) {
        $expect[] = self::$yyTokenName[$token];
    }
    $synMsg =$synMsg.('Unexpected ' . $this->tokenName($yymajor) . '(' . $TOKEN
        . '), expected one of: ' . implode(',', $expect));
    
    $this->error_msg = new WebServiceError(WebServiceErrorCode::$QUERYSYNTAX, $synMsg);
     
	$this->syntax_error = true;
#line 1366 "e:\workspace\parsergenerator\VTQL_parser.php"
    }

    /**
     * The following is executed when the parser accepts
     * 
     * %parse_accept code is inserted here
     */
    function yy_accept()
    {
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sAccept!\n", self::$yyTracePrompt);
        }
        while ($this->yyidx >= 0) {
            $stack = $this->yy_pop_parser_stack();
        }
        /* Here code is inserted which will be executed whenever the
        ** parser accepts */
#line 356 "e:\workspace\parsergenerator\VTQL_parser.y"

		if(!$this->exception){
			$this->success = true;
      	}
   #line 1390 "e:\workspace\parsergenerator\VTQL_parser.php"
    }

    /**
     * The main parser program.
     * 
     * The first argument is the major token number.  The second is
     * the token value string as scanned from the input.
     *
     * @param int the token number
     * @param mixed the token value
     * @param mixed any extra arguments that should be passed to handlers
     */
    function doParse($yymajor, $yytokenvalue)
    {
//        $yyact;            /* The parser action. */
//        $yyendofinput;     /* True if we are at the end of input */
        $yyerrorhit = 0;   /* True if yymajor has invoked an error */
        
        /* (re)initialize the parser, if necessary */
        if ($this->yyidx === null || $this->yyidx < 0) {
            /* if ($yymajor == 0) return; // not sure why this was here... */
            $this->yyidx = 0;
            $this->yyerrcnt = -1;
            $x = new VTQL_ParseryyStackEntry;
            $x->stateno = 0;
            $x->major = 0;
            $this->yystack = array();
            array_push($this->yystack, $x);
        }
        $yyendofinput = ($yymajor==0);
        
        if (self::$yyTraceFILE) {
            fprintf(self::$yyTraceFILE, "%sInput %s\n",
                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
        }
        
        do {
            $yyact = $this->yy_find_shift_action($yymajor);
            if ($yymajor < self::YYERRORSYMBOL &&
                  !$this->yy_is_expected_token($yymajor)) {
                // force a syntax error
                $yyact = self::YY_ERROR_ACTION;
            }
            if ($yyact < self::YYNSTATE) {
                $this->yy_shift($yyact, $yymajor, $yytokenvalue);
                $this->yyerrcnt--;
                if ($yyendofinput && $this->yyidx >= 0) {
                    $yymajor = 0;
                } else {
                    $yymajor = self::YYNOCODE;
                }
            } elseif ($yyact < self::YYNSTATE + self::YYNRULE) {
                $this->yy_reduce($yyact - self::YYNSTATE);
            } elseif ($yyact == self::YY_ERROR_ACTION) {
                if (self::$yyTraceFILE) {
                    fprintf(self::$yyTraceFILE, "%sSyntax Error!\n",
                        self::$yyTracePrompt);
                }
                if (self::YYERRORSYMBOL) {
                    /* A syntax error has occurred.
                    ** The response to an error depends upon whether or not the
                    ** grammar defines an error token "ERROR".  
                    **
                    ** This is what we do if the grammar does define ERROR:
                    **
                    **  * Call the %syntax_error function.
                    **
                    **  * Begin popping the stack until we enter a state where
                    **    it is legal to shift the error symbol, then shift
                    **    the error symbol.
                    **
                    **  * Set the error count to three.
                    **
                    **  * Begin accepting and shifting new tokens.  No new error
                    **    processing will occur until three tokens have been
                    **    shifted successfully.
                    **
                    */
                    if ($this->yyerrcnt < 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $yymx = $this->yystack[$this->yyidx]->major;
                    if ($yymx == self::YYERRORSYMBOL || $yyerrorhit ){
                        if (self::$yyTraceFILE) {
                            fprintf(self::$yyTraceFILE, "%sDiscard input token %s\n",
                                self::$yyTracePrompt, self::$yyTokenName[$yymajor]);
                        }
                        $this->yy_destructor($yymajor, $yytokenvalue);
                        $yymajor = self::YYNOCODE;
                    } else {
                        while ($this->yyidx >= 0 &&
                                 $yymx != self::YYERRORSYMBOL &&
        ($yyact = $this->yy_find_shift_action(self::YYERRORSYMBOL)) >= self::YYNSTATE
                              ){
                            $this->yy_pop_parser_stack();
                        }
                        if ($this->yyidx < 0 || $yymajor==0) {
                            $this->yy_destructor($yymajor, $yytokenvalue);
                            $this->yy_parse_failed();
                            $yymajor = self::YYNOCODE;
                        } elseif ($yymx != self::YYERRORSYMBOL) {
                            $u2 = 0;
                            $this->yy_shift($yyact, self::YYERRORSYMBOL, $u2);
                        }
                    }
                    $this->yyerrcnt = 3;
                    $yyerrorhit = 1;
                } else {
                    /* YYERRORSYMBOL is not defined */
                    /* This is what we do if the grammar does not define ERROR:
                    **
                    **  * Report an error message, and throw away the input token.
                    **
                    **  * If the input token is $, then fail the parse.
                    **
                    ** As before, subsequent error messages are suppressed until
                    ** three input tokens have been successfully shifted.
                    */
                    if ($this->yyerrcnt <= 0) {
                        $this->yy_syntax_error($yymajor, $yytokenvalue);
                    }
                    $this->yyerrcnt = 3;
                    $this->yy_destructor($yymajor, $yytokenvalue);
                    if ($yyendofinput) {
                        $this->yy_parse_failed();
                    }
                    $yymajor = self::YYNOCODE;
                }
            } else {
                $this->yy_accept();
                $yymajor = self::YYNOCODE;
            }            
        } while ($yymajor != self::YYNOCODE && $this->yyidx >= 0);
    }
}
