<?php
namespace Library\Database;

class Criteria extends Expression
{
    private $expressions; //armazena a lista de expressões
    private $operators;   //armazena a lista de operadores
    private $properties;  //propriedades do critério

    function __construct(){//inicializa adicionando arrays vazios as variaveis expressions e operators
        $this->expressions = array();
        $this->operators = array();
        $this->properties['offset'] = 0;
    }

    public function add(Expression $expression, $operator = self::AND_OPERATOR){
        //Na primeira vez não precisamos concatenar
        if(empty($this->expressions)){
            $operator = NULL;
        }
        //agrega o resultado da expressão a lista de expressões
        $this->expressions[] = $expression; //agregação - recebe as instancias já prontas
        $this->operators[]   = $operator;
    }

    public function dump(){//metodo abstrato da superclasse Expression-obrigatório-
        //concatena a lista de expressões
        if(is_array($this->expressions)){//é um array?
            if(count($this->expressions) > 0){
                $result = '';
                foreach($this->expressions as $i => $expression){
                    $operator = $this->operators[$i];
                    //concatena o operador com a respectiva expressão
                    $result .= $operator . $expression->dump() . ' ';
                }
                $result = trim($result);
                return "({$result})";
            }
        }
    }

    public function setProperty($property, $value){
        if(isset($value)){
            $this->properties[$property] = $value; 
        }
        else {
            $this->properties[$property] = NULL;
        }
    }

    public function getProperty($property){
        if (isset($this->properties[$property])){
            return $this->properties[$property];
        }
    }

    public function resetProperties()
    {
        $this->properties['limit'] = null;
    }

    public function setProperties($properties)
    {
        if (isset($properties['offset']) and $properties['offset']) {
            $this->properties['offset'] = (int) $properties['offset'];
        }
    }

}